<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Categories extends Component
{
    use WithFileUploads;

    public string $name = '';

    public ?TemporaryUploadedFile $image = null;

    public ?int $categoryId = null;

    public bool $editMode = false;

    /**
     * @var array<string, string>
     */
    protected array $rules = [
        'name'  => 'required|string|min:2|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    /**
     * @var array<string, string>
     */
    protected array $messages = [
        'name.required' => 'Category name is required.',
        'image.image'   => 'Please upload a valid image.',
    ];

    public function resetInput(): void
    {
        $this->reset([
            'name',
            'image',
            'categoryId',
            'editMode',
        ]);
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->image?->store('categories', 'public');

        Category::create([
            'name'  => $this->name,
            'slug'  => Str::slug($this->name),
            'image' => $imagePath,
        ]);

        $this->resetInput();

        $this->dispatch('success', message: 'Category Added Successfully');
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->image = null;
        $this->editMode = true;
    }

    public function update(): void
    {
        $this->validate();

        if (!$this->categoryId) {
            return;
        }

        $category = Category::findOrFail($this->categoryId);

        $imagePath = $category->image;

        if ($this->image instanceof TemporaryUploadedFile) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $this->image->store('categories', 'public');
        }

        $category->update([
            'name'  => $this->name,
            'slug'  => Str::slug($this->name),
            'image' => $imagePath,
        ]);

        $this->resetInput();

        $this->dispatch('success', message: 'Category Updated Successfully');
    }

    public function delete(int $id): void
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            $this->dispatch('success', message: 'Category Deleted Successfully');
        } catch (\Throwable) {
            $this->dispatch('error', message: 'Failed to delete category');
        }
    }

    public function cancelEdit(): void
    {
        $this->resetInput();
    }

    public function render(): View
    {
        return view('livewire.admin.categories', [
            'categories' => Category::latest()->get(),
        ]);
    }
}