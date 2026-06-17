<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Portfolio;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class Portfolios extends Component
{
    use WithFileUploads;

    // Single upload
    public $title        = '';
    public $category_id  = '';
    public $before_image;
    public $after_image;
    public $portfolioId;
    public $editMode     = false;

    // Bulk upload
    public $bulk_category_id = '';
    public $bulk_before_images = [];
    public $bulk_after_images  = [];

    protected function rules()
    {
        return [
            'title'        => 'nullable|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'after_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'bulk_category_id'       => 'required|exists:categories,id',
            'bulk_before_images'     => 'required|array|min:1',
            'bulk_before_images.*'   => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'bulk_after_images'      => 'required|array|min:1',
            'bulk_after_images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function resetInput()
    {
        $this->reset([
            'title',
            'category_id',
            'before_image',
            'after_image',
            'portfolioId',
            'editMode'
        ]);
    }

    public function resetBulk()
    {
        $this->reset([
            'bulk_category_id',
            'bulk_before_images',
            'bulk_after_images',
        ]);
    }

    public function save()
    {
        $this->validateOnly('title');
        $this->validateOnly('category_id');
        $this->validateOnly('before_image');
        $this->validateOnly('after_image');

        Portfolio::create([
            'category_id'  => $this->category_id,
            'title'        => $this->title,
            'before_image' => $this->before_image ? $this->before_image?->store('portfolios', 'public') : null,
            'after_image'  => $this->after_image  ? $this->after_image->store('portfolios', 'public')  : null,
        ]);

        $this->resetInput();
        $this->dispatch('success', message: 'Portfolio Added Successfully');
    }

    public function saveBulk()
    {
        $this->validate([
            'bulk_category_id'     => 'required|exists:categories,id',
            'bulk_before_images'   => 'required|array|min:1',
            'bulk_before_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'bulk_after_images'    => 'required|array|min:1',
            'bulk_after_images.*'  => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $befores = $this->bulk_before_images;
        $afters  = $this->bulk_after_images;

        if (count($befores) !== count($afters)) {
            $this->dispatch('error', message: 'Before aur After images ki count same honi chahiye!');
            return;
        }

        foreach ($befores as $i => $before) {
            Portfolio::create([
                'category_id'  => $this->bulk_category_id,
                'title' => '',
                'before_image' => $before->store('portfolios', 'public'),
                'after_image'  => $afters[$i]->store('portfolios', 'public'),
            ]);
        }

        $this->resetBulk();
        $this->dispatch('success', message: count($befores) . ' Portfolios Added Successfully');
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $this->portfolioId  = $portfolio->id;
        $this->title        = $portfolio->title;
        $this->category_id  = $portfolio->category_id;
        $this->before_image = null;
        $this->after_image  = null;
        $this->editMode     = true;
    }

    public function update()
    {
        $this->validate([
            'title'        => 'nullable|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'before_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'after_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $portfolio = Portfolio::findOrFail($this->portfolioId);
        $data = [
            'category_id' => $this->category_id,
            'title'       => $this->title,
        ];

        foreach (['before_image', 'after_image'] as $field) {
            if ($this->$field) {
                if ($portfolio->$field) {
                    Storage::disk('public')->delete($portfolio->$field);
                }
                $data[$field] = $this->$field->store('portfolios', 'public');
            }
        }

        $portfolio->update($data);
        $this->resetInput();
        $this->dispatch('success', message: 'Portfolio Updated Successfully');
    }

    public function delete($id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);
            foreach (['before_image', 'after_image'] as $field) {
                if ($portfolio->$field) {
                    Storage::disk('public')->delete($portfolio->$field);
                }
            }
            $portfolio->delete();
            $this->dispatch('success', message: 'Portfolio Deleted Successfully');
        } catch (\Throwable $e) {
            $this->dispatch('error', message: 'Failed to delete portfolio');
        }
    }

    public $selectedCategory = 'all';

    public function filterCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    // public function render()
    // {
    //     return view('livewire.admin.portfolios', [
    //         'portfolios' => Portfolio::with('category')->latest()->get(),
    //         'categories' => Category::latest()->get(),
    //     ]);
    // }
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
