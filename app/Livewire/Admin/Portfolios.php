<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Portfolios extends Component
{
    use WithFileUploads;

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
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'after_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'bulk_category_id' => 'required|exists:categories,id',
            'bulk_before_images' => 'required|array|min:1',
            'bulk_before_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'bulk_after_images' => 'required|array|min:1',
            'bulk_after_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
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
        ]);
    }

    public function resetBulk(): void
    {
        $this->reset([
            'bulk_category_id',
            'bulk_before_images',
            'bulk_after_images',
        ]);
    }

    public function save(): void
    {
        $this->validate();

        Portfolio::create([
            'category_id' => $this->category_id,
            'title' => $this->title,
            'before_image' => $this->before_image?->store('portfolios', 'public'),
            'after_image' => $this->after_image?->store('portfolios', 'public'),
        ]);

        $this->resetInput();

        $this->dispatch('success', message: 'Portfolio Added Successfully');
    }

    public function saveBulk(): void
    {
        $this->validate();

        if (count($this->bulk_before_images) !== count($this->bulk_after_images)) {
            $this->dispatch('error', message: 'Before/After images count must match');
            return;
        }

        foreach ($this->bulk_before_images as $i => $before) {
            $after = $this->bulk_after_images[$i] ?? null;

            Portfolio::create([
                'category_id' => $this->bulk_category_id,
                'title' => '',
                'before_image' => $before->store('portfolios', 'public'),
                'after_image' => $after?->store('portfolios', 'public'),
            ]);
        }

        $this->resetBulk();

        $this->dispatch(
            'success',
            message: 'Bulk portfolios uploaded successfully'
        );
    }

    public function edit(int $id): void
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->portfolioId = $portfolio->id;
        $this->title = $portfolio->title;
        $this->category_id = $portfolio->category_id;

        $this->before_image = null;
        $this->after_image = null;

        $this->editMode = true;
    }

    public function update(): void
    {
        $this->validate();

        if (!$this->portfolioId) {
            return;
        }

        $portfolio = Portfolio::findOrFail($this->portfolioId);

        $data = [
            'category_id' => $this->category_id,
            'title' => $this->title,
        ];

        if ($this->before_image instanceof TemporaryUploadedFile) {
            if ($portfolio->before_image) {
                Storage::disk('public')->delete($portfolio->before_image);
            }

            $data['before_image'] = $this->before_image->store('portfolios', 'public');
        }

        if ($this->after_image instanceof TemporaryUploadedFile) {
            if ($portfolio->after_image) {
                Storage::disk('public')->delete($portfolio->after_image);
            }

            $data['after_image'] = $this->after_image->store('portfolios', 'public');
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
        } catch (\Throwable) {
            $this->dispatch('error', message: 'Failed to delete portfolio');
        }
    }

    public function filterCategory(string|int $categoryId): void
    {
        $this->selectedCategory = (string) $categoryId;
    }

    public function render(): View
    {
        $query = Portfolio::with('category')->latest();

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.admin.portfolios', [
            'portfolios' => $query->get(),
            'categories' => Category::latest()->get(),
        ]);
    }
}