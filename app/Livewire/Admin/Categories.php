<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $name = '';

    public ?TemporaryUploadedFile $image = null;

    public ?string $existingImage = null;

    public ?int $categoryId = null;

    public bool $editMode = false;

    public int $perPage = 5;

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
            'existingImage',
        ]);
    }

    private function generateFilename(TemporaryUploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext  = $file->getClientOriginalExtension();
        return $name . '_' . time() . '.' . $ext;
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->image
            ? $this->image->storeAs('categories', $this->generateFilename($this->image), 'public')
            : null;

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

        $this->existingImage = $category->image;

        $this->image = null;
        $this->editMode = true;

        $this->dispatch('edit-mode-activated');
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

            $imagePath = $this->image->storeAs('categories', $this->generateFilename($this->image), 'public');
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

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.admin.categories', [
            'categories' => Category::latest()->paginate($this->perPage),
        ]);
    }
}
