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

    /**
     * null = Main Category
     * ID   = Sub Category of selected parent
     */
    public ?int $parentId = null;

    public bool $editMode = false;

    public int|string $perPage = 5;

    /**
     * @var array<string, string>
     */
    protected array $rules = [
        'name' => 'required|string|min:2|max:255',
        'parentId' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    /**
     * @var array<string, string>
     */
    protected array $messages = [
        'name.required' => 'Category name is required.',
        'parentId.exists' => 'Selected parent category does not exist.',
        'image.image' => 'Please upload a valid image.',
        'image.mimes' => 'Image must be JPG, JPEG, PNG or WEBP.',
        'image.max' => 'Image size must not exceed 2MB.',
    ];

    /**
     * Reset form inputs.
     */
    public function resetInput(): void
    {
        $this->reset([
            'name',
            'image',
            'categoryId',
            'parentId',
            'editMode',
            'existingImage',
        ]);

        $this->resetValidation();
    }

    /**
     * Generate unique filename for uploaded image.
     */
    private function generateFilename(TemporaryUploadedFile $file): string
    {
        $name = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $name = Str::slug($name);

        $ext = $file->getClientOriginalExtension();

        return $name . '_' . time() . '.' . $ext;
    }

    /**
     * Validate that selected parent is a MAIN category.
     *
     * Subcategory cannot be used as another subcategory's parent.
     */
    private function validateParentCategory(): bool
    {
        if (!$this->parentId) {
            return true;
        }

        $parent = Category::find($this->parentId);

        if (!$parent) {
            $this->addError(
                'parentId',
                'Selected parent category does not exist.'
            );

            return false;
        }

        /*
         * Parent itself must be a main category.
         *
         * Main category:
         * parent_id = null
         */
        if ($parent->parent_id !== null) {
            $this->addError(
                'parentId',
                'A subcategory cannot be used as a parent.'
            );

            return false;
        }

        /*
         * Category cannot be its own parent.
         */
        if (
            $this->categoryId !== null &&
            $this->parentId === $this->categoryId
        ) {
            $this->addError(
                'parentId',
                'A category cannot be its own parent.'
            );

            return false;
        }

        return true;
    }

    /**
     * Check duplicate slug within the same parent.
     *
     * Example:
     *
     * Web Design
     *   └── Landing Page
     *
     * Logo Design
     *   └── Landing Page
     *
     * This is allowed because they have different parents.
     */
    private function hasDuplicateSlug(string $slug): bool
    {
        $query = Category::where('slug', $slug)
            ->where('parent_id', $this->parentId);

        if ($this->categoryId !== null) {
            $query->where('id', '!=', $this->categoryId);
        }

        return $query->exists();
    }

    /**
     * Save new category.
     */
    public function save(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'parentId' => [
                'nullable',
                'exists:categories,id',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
         * Validate parent.
         *
         * null parentId = Main Category
         * parentId = ID   = Sub Category
         */
        if (!$this->validateParentCategory()) {
            return;
        }

        $slug = Str::slug($this->name);

        /*
         * Duplicate check is done within the same parent.
         */
        if ($this->hasDuplicateSlug($slug)) {
            $this->addError(
                'name',
                'A category with this name already exists here.'
            );

            return;
        }

        /*
         * Store image.
         */
        $imagePath = null;

        if ($this->image instanceof TemporaryUploadedFile) {
            $imagePath = $this->image->storeAs(
                'categories',
                $this->generateFilename($this->image),
                'public'
            );
        }

        /*
         * Create category.
         */
        Category::create([
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'slug' => $slug,
            'image' => $imagePath,
        ]);

        /*
         * Reset form.
         */
        $this->resetInput();

        $this->dispatch('category-form-reset');

        $this->dispatch(
            'success',
            message: 'Category Added Successfully'
        );
    }

    /**
     * Load category into edit form.
     */
    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;

        $this->name = $category->name;

        /*
         * Load parent.
         *
         * null = Main Category
         * ID   = Sub Category
         */
        $this->parentId = $category->parent_id;

        $this->existingImage = $category->image;

        $this->image = null;

        $this->editMode = true;

        $this->resetValidation();

        $this->dispatch('edit-mode-activated');
    }

    /**
     * Update existing category.
     */
    public function update(): void
    {
        if (!$this->categoryId) {
            return;
        }

        $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'parentId' => [
                'nullable',
                'exists:categories,id',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
         * Validate parent category.
         */
        if (!$this->validateParentCategory()) {
            return;
        }

        $category = Category::findOrFail($this->categoryId);

        /*
         * Prevent changing a MAIN category into one of its own
         * descendants.
         *
         * Since our system supports only one subcategory level,
         * checking the selected parent is enough.
         */

        $slug = Str::slug($this->name);

        /*
         * Duplicate check.
         *
         * Same name is allowed under different parents.
         */
        if ($this->hasDuplicateSlug($slug)) {
            $this->addError(
                'name',
                'A category with this name already exists here.'
            );

            return;
        }

        /*
         * Keep old image unless a new image is uploaded.
         */
        $imagePath = $category->image;

        /*
         * Replace old image.
         */
        if ($this->image instanceof TemporaryUploadedFile) {

            if ($category->image) {
                Storage::disk('public')->delete(
                    $category->image
                );
            }

            $imagePath = $this->image->storeAs(
                'categories',
                $this->generateFilename($this->image),
                'public'
            );
        }

        /*
         * Update category.
         */
        $category->update([
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'slug' => $slug,
            'image' => $imagePath,
        ]);

        /*
         * Reset form.
         */
        $this->resetInput();

        $this->dispatch('category-form-reset');

        $this->dispatch('category-updated');

        $this->dispatch(
            'success',
            message: 'Category Updated Successfully'
        );
    }

    /**
     * Delete category.
     */
    public function delete(int $id): void
    {
        try {

            $category = Category::with('children')
                ->findOrFail($id);

            /*
             * Delete category image.
             */
            if ($category->image) {
                Storage::disk('public')->delete(
                    $category->image
                );
            }

            /*
             * Delete child category images first.
             */
            foreach ($category->children as $child) {

                if ($child->image) {
                    Storage::disk('public')->delete(
                        $child->image
                    );
                }
            }

            /*
             * Because parent_id uses nullOnDelete,
             * deleting a parent would otherwise leave children
             * as main categories.
             *
             * We delete children along with the parent.
             */
            $category->children()->delete();

            /*
             * Delete parent.
             */
            $category->delete();

            $this->dispatch(
                'success',
                message: 'Category Deleted Successfully'
            );
        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'error',
                message: 'Failed to delete category'
            );
        }
    }

    /**
     * Cancel edit mode.
     */
    public function cancelEdit(): void
    {
        $this->resetInput();

        $this->dispatch('category-form-reset');
    }

    /**
     * Reset pagination when per-page changes.
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Render categories.
     *
     * Only MAIN categories are displayed as cards.
     * Their children/subcategories are loaded using eager loading.
     */
    public function render(): View
    {
        /*
         * Main categories only.
         *
         * with('children') loads their subcategories.
         */
        // $query = Category::with([
        //     'children' => function ($query) {
        //         $query->latest();
        //     },
        // ])
        //     ->whereNull('parent_id')
        //     ->latest();
        $query = Category::with('parent')
            ->latest();

        /*
         * Pagination.
         */
        if ($this->perPage === 'all') {

            $categories = $query->get();
        } else {

            $categories = $query->paginate(
                (int) $this->perPage
            );
        }

        /*
         * Only MAIN categories are allowed
         * in the Parent Category dropdown.
         */
        $mainCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'mainCategories' => $mainCategories,
        ]);
    }
}
