<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Categories extends Component
{
    use WithFileUploads;

    public $name = '';
    public $image;
    public $categoryId;
    public $editMode = false;

    protected $rules = [
        'name'  => 'required|string|min:2|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    protected $messages = [
        'name.required' => 'Category name is required.',
        'image.image'   => 'Please upload a valid image.',
    ];

    public function resetInput()
    {
        $this->reset([
            'name',
            'image',
            'categoryId',
            'editMode'
        ]);
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('categories', 'public');
        }

        Category::create([
            'name'  => $this->name,
            'slug'  => Str::slug($this->name),
            'image' => $imagePath,
        ]);

        $this->resetInput();

        $this->dispatch(
            'success',
            message: 'Category Added Successfully'
        );
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->image = null;
        $this->editMode = true;
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->categoryId);

        $imagePath = $category->image;

        if ($this->image) {

            // old image delete
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

        $this->dispatch(
            'success',
            message: 'Category Updated Successfully'
        );
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            $this->dispatch('success', message: 'Category Deleted Successfully');
        } catch (\Throwable $e) {
            $this->dispatch('error', message: 'Failed to delete category');
        }
    }

    public function cancelEdit()
    {
        $this->resetInput();
    }

    public function render()
    {
        return view('livewire.admin.categories', [
            'categories' => Category::latest()->get()
        ]);
    }
}
