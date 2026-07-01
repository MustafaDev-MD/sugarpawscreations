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

    public int $perPage = 10;

    public bool $remove_before_image = false;

    // Single upload
    public string $title = '';
    public ?int $category_id = null;

    public ?TemporaryUploadedFile $before_image = null;
    public ?TemporaryUploadedFile $after_image = null;

    public ?int $portfolioId = null;
    public bool $editMode = false;

    // Bulk upload
    public ?int $bulk_category_id = null;

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $bulk_before_images = [];

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $bulk_after_images = [];

    public string $selectedCategory = 'all';

    /**
     * Rules for the single create/update form.
     *
     * @return array<string, mixed>
     */
    protected function singleRules(): array
    {
        return [
            'title'        => 'nullable|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'after_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    /**
     * Rules for the bulk upload form.
     *
     * @return array<string, mixed>
     */
    // protected function bulkRules(): array
    // {
    //     return [
    //         'bulk_category_id'      => 'required|exists:categories,id',
    //         // 'bulk_before_images'    => 'required|array|min:1',
    //         'bulk_before_images' => 'nullable|array',
    //         // 'bulk_before_images.*'  => 'image|mimes:jpg,jpeg,png,webp|max:4096',
    //         'bulk_before_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    //         'bulk_after_images'     => 'required|array|min:1',
    //         'bulk_after_images.*'   => 'image|mimes:jpg,jpeg,png,webp|max:4096',
    //     ];
    // }

    private function generateFilename(TemporaryUploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext  = $file->getClientOriginalExtension();
        return $name . '_' . time() . '.' . $ext;
    }

    public function resetInput(): void
    {
        $this->reset([
            'title',
            'category_id',
            'before_image',
            'after_image',
            'portfolioId',
            'editMode',
            'currentPortfolio',
            'remove_before_image',
        ]);

        $this->resetErrorBag();
    }

    public function resetBulk(): void
    {
        $this->reset([
            'bulk_category_id',
            'bulk_before_images',
            'bulk_after_images',
        ]);

        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate($this->singleRules());

        $category = Category::findOrFail($this->category_id);

        Portfolio::create([
            'category_id'  => $this->category_id,
            'title'        => $this->title,
            // 'before_image' => $this->before_image
            //     ? $this->before_image->storeAs('portfolios', $this->generateFilename($this->before_image), 'public')
            //     : null,
            'before_image' => $category->has_before_image && $this->before_image
                ? $this->before_image->storeAs(
                    'portfolios',
                    $this->generateFilename($this->before_image),
                    'public'
                )
                : null,
            'after_image'  => $this->after_image
                ? $this->after_image->storeAs('portfolios', $this->generateFilename($this->after_image), 'public')
                : null,
        ]);

        $this->resetInput();

        $this->dispatch('success', message: 'Portfolio Added Successfully');
    }

    // public function saveBulk(): void
    // {
    //     // $this->validate($this->bulkRules());

    //     // if (count($this->bulk_before_images) !== count($this->bulk_after_images)) {
    //     //     $this->dispatch('error', message: 'Before/After images count must match');
    //     //     return;
    //     // }

    //     $category = Category::find($this->bulk_category_id);

    //     // if (
    //     //     $category?->has_before_image &&
    //     //     count($this->bulk_before_images) !== count($this->bulk_after_images)
    //     // ) {
    //     //     $this->dispatch('error', message: 'Before/After images count must match');
    //     //     return;
    //     // }

    //     if (
    //         $category?->has_before_image &&
    //         count($this->bulk_before_images) > 0 &&
    //         count($this->bulk_before_images) !== count($this->bulk_after_images)
    //     ) {
    //         $this->dispatch('error', message: 'Before/After images count must match');
    //         return;
    //     }

    //     // foreach ($this->bulk_before_images as $i => $before) {
    //     //     $after = $this->bulk_after_images[$i] ?? null;

    //     //     Portfolio::create([
    //     //         'category_id'  => $this->bulk_category_id,
    //     //         'title'        => '',
    //     //         'before_image' => $before->storeAs('portfolios', $this->generateFilename($before), 'public'),
    //     //         'after_image'  => $after
    //     //             ? $after->storeAs('portfolios', $this->generateFilename($after), 'public')
    //     //             : null,
    //     //     ]);
    //     // }

    //     $category = Category::findOrFail($this->bulk_category_id);

    //     $rules = [
    //         'bulk_category_id'    => 'required|exists:categories,id',
    //         'bulk_after_images'   => 'required|array|min:1',
    //         'bulk_after_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
    //     ];

    //     if ($category->has_before_image) {
    //         $rules['bulk_before_images'] = 'required|array|min:1';
    //         $rules['bulk_before_images.*'] = 'image|mimes:jpg,jpeg,png,webp|max:4096';
    //     }

    //     $this->validate($rules);

    //     if ($category->has_before_image) {

    //         // if (count($this->bulk_before_images) !== count($this->bulk_after_images)) {
    //         //     $this->dispatch('error', message: 'Before/After images count must match');
    //         //     return;
    //         // }

    //         if (
    //             count($this->bulk_before_images) > 0 &&
    //             count($this->bulk_before_images) !== count($this->bulk_after_images)
    //         ) {
    //             $this->dispatch('error', message: 'Before/After images count must match');
    //             return;
    //         }

    //         foreach ($this->bulk_before_images as $i => $before) {

    //             $after = $this->bulk_after_images[$i];

    //             Portfolio::create([
    //                 'category_id' => $this->bulk_category_id,
    //                 'title' => '',
    //                 'before_image' => $before->storeAs(
    //                     'portfolios',
    //                     $this->generateFilename($before),
    //                     'public'
    //                 ),
    //                 'after_image' => $after->storeAs(
    //                     'portfolios',
    //                     $this->generateFilename($after),
    //                     'public'
    //                 ),
    //             ]);
    //         }
    //     } else {

    //         foreach ($this->bulk_after_images as $after) {

    //             Portfolio::create([
    //                 'category_id' => $this->bulk_category_id,
    //                 'title' => '',
    //                 'before_image' => null,
    //                 'after_image' => $after->storeAs(
    //                     'portfolios',
    //                     $this->generateFilename($after),
    //                     'public'
    //                 ),
    //             ]);
    //         }
    //     }

    //     $this->resetBulk();

    //     $this->dispatch('success', message: 'Bulk portfolios uploaded successfully');
    // }

    public function saveBulk(): void
    {
        // 1. Initial validation for the category ID
        $this->validate([
            'bulk_category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::findOrFail($this->bulk_category_id);

        // 2. Base rules: After images are always required for a portfolio entry
        $rules = [
            'bulk_after_images'   => 'required|array|min:1',
            'bulk_after_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        // 3. Conditional strict validation rules only if before images are present
        if ($category->has_before_image && !empty($this->bulk_before_images)) {
            $rules['bulk_before_images']   = 'required|array|min:1';
            $rules['bulk_before_images.*'] = 'image|mimes:jpg,jpeg,png,webp|max:4096';
        }

        $this->validate($rules);

        // 4. If before images are uploaded, enforce a strict matching count pair
        if ($category->has_before_image && !empty($this->bulk_before_images)) {
            if (count($this->bulk_before_images) !== count($this->bulk_after_images)) {
                $this->dispatch('error', message: 'Before/After images count must match');
                return;
            }

            // Loop through pairs safely since counts match
            foreach ($this->bulk_before_images as $i => $before) {
                $after = $this->bulk_after_images[$i];

                Portfolio::create([
                    'category_id'  => $this->bulk_category_id,
                    'title'        => '',
                    'before_image' => $before->storeAs('portfolios', $this->generateFilename($before), 'public'),
                    'after_image'  => $after->storeAs('portfolios', $this->generateFilename($after), 'public'),
                ]);
            }
        } else {
            // 5. If no before images were uploaded (or category doesn't support them), just save after images
            foreach ($this->bulk_after_images as $after) {
                Portfolio::create([
                    'category_id'  => $this->bulk_category_id,
                    'title'        => '',
                    'before_image' => null,
                    'after_image'  => $after->storeAs('portfolios', $this->generateFilename($after), 'public'),
                ]);
            }
        }

        $this->resetBulk();

        $this->dispatch('success', message: 'Bulk portfolios uploaded successfully');
    }

    public function edit(int $id): void
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->currentPortfolio = $portfolio;

        $this->portfolioId  = $portfolio->id;
        $this->title        = $portfolio->title;
        $this->category_id  = $portfolio->category_id;

        $this->before_image = null;
        $this->after_image  = null;

        $this->remove_before_image = false;

        $this->editMode = true;

        $this->dispatch('edit-mode-activated');
    }

    public function update(): void
    {
        $this->validate($this->singleRules());

        $category = Category::findOrFail($this->category_id);

        if (!$this->portfolioId) {
            return;
        }

        $portfolio = Portfolio::findOrFail($this->portfolioId);

        $data = [
            'category_id' => $this->category_id,
            'title'       => $this->title,
        ];

        // if ($this->before_image instanceof TemporaryUploadedFile) {
        //     if ($portfolio->before_image) {
        //         Storage::disk('public')->delete($portfolio->before_image);
        //     }

        //     $data['before_image'] = $this->before_image->storeAs(
        //         'portfolios',
        //         $this->generateFilename($this->before_image),
        //         'public'
        //     );
        // }

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

        // if ($category->has_before_image) {

        //     if ($this->before_image instanceof TemporaryUploadedFile) {

        //         if ($portfolio->before_image) {
        //             Storage::disk('public')->delete($portfolio->before_image);
        //         }

        //         $data['before_image'] = $this->before_image->storeAs(
        //             'portfolios',
        //             $this->generateFilename($this->before_image),
        //             'public'
        //         );
        //     }
        // } else {

        //     if ($portfolio->before_image) {
        //         Storage::disk('public')->delete($portfolio->before_image);
        //     }

        //     $data['before_image'] = null;
        // }

        if ($this->after_image instanceof TemporaryUploadedFile) {
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

        $this->dispatch('success', message: 'Portfolio Updated Successfully');
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
        // $query = Portfolio::with('category')->latest();

        $query = Portfolio::query()
            // ->with(['category:id,name'])
            ->with(['category:id,name,has_before_image'])
            ->latest();

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.admin.portfolios', [
            'portfolios' => $query->paginate($this->perPage),
            // 'categories' => Category::select('id', 'name')->latest()->get(),
            'categories' => Category::select('id', 'name', 'has_before_image')
                ->latest()
                ->get(),
        ]);
    }
}
