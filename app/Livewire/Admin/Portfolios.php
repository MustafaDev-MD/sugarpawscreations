<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Portfolios extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected string  $paginationTheme = 'tailwind';

    public ?Portfolio $currentPortfolio = null;

    public int|string $perPage = 10;

    public bool $remove_before_image = false;
    public bool $remove_after_image = false;

    // Single upload
    public string $title = '';

    public ?int $category_id = null;
    public ?int $subcategory_id = null;

    public ?TemporaryUploadedFile $before_image = null;
    public ?TemporaryUploadedFile $after_image = null;

    public ?int $portfolioId = null;
    public bool $editMode = false;

    // Bulk upload
    public ?int $bulk_category_id = null;
    public ?int $bulk_subcategory_id = null;

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $bulk_before_images = [];

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $bulk_after_images = [];

    public string $selectedCategory = 'all';

    private function showValidationErrors(\Illuminate\Validation\ValidationException $e): void
    {
        foreach ($e->validator->errors()->all() as $message) {
            $this->dispatch('error', message: $message);
        }
    }

    /**
     * Rules for the single create/update form.
     *
     * @return array<string, mixed>
     */
    protected function singleRules(): array
    {
        return [
            'title' => 'nullable|string|max:255',

            'category_id' => 'required|exists:categories,id',

            'subcategory_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if (!$value || !$this->category_id) {
                        return;
                    }

                    $exists = Category::where('id', $value)
                        ->where('parent_id', $this->category_id)
                        ->exists();

                    if (!$exists) {
                        $fail('The selected subcategory does not belong to the selected category.');
                    }
                },
            ],

            // 'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,mp4|max:102400',
            'before_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:102400',

            // 'after_image' => $this->editMode
            //     ? [
            //         'required_without:currentPortfolio.after_image',
            //         'nullable',
            //         'image',
            //         'mimes:jpg,jpeg,png,webp,gif,mp4',
            //         'max:102400',
            //     ]
            //     : 'required|image|mimes:jpg,jpeg,png,webp,gif,mp4|max:102400',

            'after_image' => $this->editMode
                ? [
                    'required_without:currentPortfolio.after_image',
                    'nullable',
                    'file',
                    'mimes:jpg,jpeg,png,webp,gif',
                    'max:102400',
                ]
                : 'required|file|mimes:jpg,jpeg,png,webp,gif|max:102400',
        ];
    }

    public function getSubcategoriesProperty()
    {
        if (!$this->category_id) {
            return collect();
        }

        return Category::query()
            ->where('parent_id', $this->category_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function getBulkSubcategoriesProperty()
    {
        if (!$this->bulk_category_id) {
            return collect();
        }

        return Category::query()
            ->where('parent_id', $this->bulk_category_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function updatedCategoryId(): void
    {
        $this->subcategory_id = null;
    }

    public function updatedBulkCategoryId(): void
    {
        $this->bulk_subcategory_id = null;
    }

    private function generateFilename(TemporaryUploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext  = $file->getClientOriginalExtension();

        return $name . '_' . uniqid() . '.' . $ext;
    }

    public function resetInput(): void
    {
        $this->reset([
            'title',
            'category_id',
            'subcategory_id',
            'before_image',
            'after_image',
            'portfolioId',
            'editMode',
            'currentPortfolio',
            'remove_before_image',
            'remove_after_image',
        ]);

        $this->resetErrorBag();
    }

    public function resetBulk(): void
    {
        $this->reset([
            'bulk_category_id',
            'bulk_subcategory_id',
            'bulk_before_images',
            'bulk_after_images',
        ]);

        $this->resetErrorBag();
    }

    public function save(): void
    {
        try {
            $this->validate($this->singleRules());

            $category = Category::findOrFail($this->category_id);

            Portfolio::create([
                'category_id' => $this->category_id,
                'subcategory_id' => $this->subcategory_id,
                'title' => $this->title,
                'before_image' => $category->has_before_image && $this->before_image
                    ? $this->before_image->storeAs(
                        'portfolios',
                        $this->generateFilename($this->before_image),
                        'public'
                    )
                    : null,
                'after_image' => $this->after_image
                    ? $this->after_image->storeAs(
                        'portfolios',
                        $this->generateFilename($this->after_image),
                        'public'
                    )
                    : null,
            ]);

            $this->resetInput();

            $this->dispatch('$refresh');
            $this->dispatch('reset-previews');

            $this->dispatch('success', message: 'Portfolio Added Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->showValidationErrors($e);

            throw $e;
        }
    }

    public function removeBulkBeforeImage(int $index): void
    {
        if (isset($this->bulk_before_images[$index])) {
            unset($this->bulk_before_images[$index]);
            $this->bulk_before_images = array_values($this->bulk_before_images);
        }

        $this->dispatch('bulk-before-image-removed', index: $index);
    }

    public function removeBulkAfterImage(int $index): void
    {
        if (isset($this->bulk_after_images[$index])) {
            unset($this->bulk_after_images[$index]);
            $this->bulk_after_images = array_values($this->bulk_after_images);
        }

        $this->dispatch('bulk-after-image-removed', index: $index);
    }

    public function saveBulk(): void
    {
        try {

            $this->validate([
                'bulk_category_id' => 'required|exists:categories,id',
                'bulk_subcategory_id' => [
                    'nullable',
                    'exists:categories,id',
                    function ($attribute, $value, $fail) {
                        if (!$value || !$this->bulk_category_id) {
                            return;
                        }

                        $exists = Category::where('id', $value)
                            ->where('parent_id', $this->bulk_category_id)
                            ->exists();

                        if (!$exists) {
                            $fail('The selected subcategory does not belong to the selected category.');
                        }
                    },
                ],
            ]);

            $category = Category::findOrFail($this->bulk_category_id);

            $rules = [
                'bulk_after_images' => 'required|array|min:1|max:20',
                'bulk_after_images.*' => 'file|mimes:jpg,jpeg,png,webp,gif|max:102400',
            ];

            if ($category->has_before_image && !empty($this->bulk_before_images)) {
                $rules['bulk_before_images'] = 'required|array|min:1|max:20';
                $rules['bulk_before_images.*'] =
                    'file|mimes:jpg,jpeg,png,webp,gif|max:102400';
            }

            $this->validate($rules);

            if ($category->has_before_image && !empty($this->bulk_before_images)) {

                if (count($this->bulk_before_images) !== count($this->bulk_after_images)) {
                    $this->dispatch(
                        'error',
                        message: 'Before/After images count must match.'
                    );

                    return;
                }

                foreach ($this->bulk_before_images as $i => $before) {
                    $after = $this->bulk_after_images[$i];

                    Portfolio::create([
                        'category_id' => $this->bulk_category_id,
                        'subcategory_id' => $this->bulk_subcategory_id,
                        'title' => '',
                        'before_image' => $before->storeAs(
                            'portfolios',
                            $this->generateFilename($before),
                            'public'
                        ),
                        'after_image' => $after->storeAs(
                            'portfolios',
                            $this->generateFilename($after),
                            'public'
                        ),
                    ]);
                }
            } else {

                foreach ($this->bulk_after_images as $after) {
                    Portfolio::create([
                        'category_id' => $this->bulk_category_id,
                        'subcategory_id' => $this->bulk_subcategory_id,
                        'title' => '',
                        'before_image' => null,
                        'after_image' => $after->storeAs(
                            'portfolios',
                            $this->generateFilename($after),
                            'public'
                        ),
                    ]);
                }
            }

            $this->resetBulk();

            $this->dispatch('reset-bulk-previews');

            $this->dispatch(
                'success',
                message: 'Bulk portfolios uploaded successfully'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->showValidationErrors($e);

            throw $e;
        }
    }

    public function edit(int $id): void
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->currentPortfolio = $portfolio;

        $this->portfolioId  = $portfolio->id;
        $this->title        = $portfolio->title;
        $this->category_id  = $portfolio->category_id;
        $this->subcategory_id = $portfolio->subcategory_id;

        $this->before_image = null;
        $this->after_image  = null;

        $this->remove_before_image = false;
        $this->remove_after_image = false;

        $this->editMode = true;

        $this->dispatch('edit-mode-activated');
    }

    public function removeBeforeImage(): void
    {
        $this->before_image = null;
        $this->remove_before_image = true;

        $this->dispatch('before-image-removed');
    }

    public function removeAfterImage(): void
    {
        $this->after_image = null;
        $this->remove_after_image = true;

        $this->dispatch('after-image-removed');
    }

    public function update(): void
    {
        try {
            $this->validate([
                'title' => 'nullable|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,mp4|max:102400',
                'after_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,mp4|max:102400',
            ]);

            $category = Category::findOrFail($this->category_id);

            if (!$this->portfolioId) {
                return;
            }

            $portfolio = Portfolio::findOrFail($this->portfolioId);

            // After image must always exist after update.
            // If existing image is removed, a new image must be uploaded.
            if (
                $this->remove_after_image &&
                !($this->after_image instanceof TemporaryUploadedFile)
            ) {
                $this->addError(
                    'after_image',
                    'After image is required.'
                );

                return;
            }

            // If there is no existing After image and no new image,
            // update must not continue.
            if (
                !$portfolio->after_image &&
                !($this->after_image instanceof TemporaryUploadedFile)
            ) {
                $this->addError(
                    'after_image',
                    'After image is required.'
                );

                return;
            }

            $data = [
                'category_id' => $this->category_id,
                'subcategory_id' => $this->subcategory_id,
                'title' => $this->title,
            ];

            // if (!$this->portfolioId) {
            //     return;
            // }

            // $portfolio = Portfolio::findOrFail($this->portfolioId);

            // $data = [
            //     'category_id' => $this->category_id,
            //     'title'       => $this->title,
            // ];

            if ($this->remove_before_image) {

                if ($portfolio->before_image) {
                    Storage::disk('public')->delete($portfolio->before_image);
                }

                $data['before_image'] = null;
            } elseif ($category->has_before_image) {

                if ($this->before_image instanceof TemporaryUploadedFile) {

                    if ($portfolio->before_image) {
                        Storage::disk('public')->delete($portfolio->before_image);
                    }

                    $data['before_image'] = $this->before_image->storeAs(
                        'portfolios',
                        $this->generateFilename($this->before_image),
                        'public'
                    );
                }
            } else {

                if ($portfolio->before_image) {
                    Storage::disk('public')->delete($portfolio->before_image);
                }

                $data['before_image'] = null;
            }

            if ($this->remove_after_image) {

                if ($portfolio->after_image) {
                    Storage::disk('public')->delete($portfolio->after_image);
                }

                $data['after_image'] = null;
            } elseif ($this->after_image instanceof TemporaryUploadedFile) {

                if ($portfolio->after_image) {
                    Storage::disk('public')->delete($portfolio->after_image);
                }

                $data['after_image'] = $this->after_image->storeAs(
                    'portfolios',
                    $this->generateFilename($this->after_image),
                    'public'
                );
            }

            $portfolio->update($data);

            $this->resetInput();

            $this->dispatch('reset-previews');

            $this->dispatch('success', message: 'Portfolio Updated Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->showValidationErrors($e);

            throw $e;
        }
    }

    public function delete(int $id): void
    {
        try {
            $portfolio = Portfolio::findOrFail($id);

            if ($portfolio->before_image) {
                Storage::disk('public')->delete($portfolio->before_image);
            }

            if ($portfolio->after_image) {
                Storage::disk('public')->delete($portfolio->after_image);
            }

            $portfolio->delete();

            $this->dispatch('$refresh');

            $this->dispatch('success', message: 'Portfolio Deleted Successfully');
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());

            $this->dispatch('error', message: 'Failed to delete portfolio');
        }
    }

    public function filterCategory(string|int $categoryId): void
    {
        $this->selectedCategory = (string) $categoryId;

        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Portfolio::query()
            ->with([
                'category:id,name,has_before_image',
                'subcategory:id,name',
            ])
            ->latest();

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        // "All" ka matlab hai sab records ek hi page par — total count ko
        // hi perPage bana dete hain taake paginator theek se kaam kare
        // (aur agar table khaali ho to at least 1 pass karte hain warna
        // paginate() error deta hai).
        $perPage = $this->perPage === 'all'
            ? max((clone $query)->count(), 1)
            : (int) $this->perPage;

        return view('livewire.admin.portfolios', [
            'portfolios' => $query->paginate($perPage),
            'categories' => Category::query()
                ->whereNull('parent_id')
                ->select('id', 'name', 'has_before_image')
                ->latest()
                ->get(),
        ]);
    }
}
