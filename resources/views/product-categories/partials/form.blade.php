<div class="space-y-5">

    <x-form.input name="name"
                  label="Category Name"
                  :required="true"
                  placeholder="e.g. LPG Gas Cylinders, Regulators">
        {{ $category->name ?? '' }}
    </x-form.input>

    <x-form.textarea name="description"
                     label="Description"
                     hint="Optional"
                     rows="4"
                     placeholder="Provide operational notes or classification details...">
        {{ $category->description ?? '' }}
    </x-form.textarea>

    <x-form.toggle name="is_active"
                   label="Operational Active Status"
                   description="When enabled, products under this category will be available for sale on POS terminals."
                   :checked="(bool) old('is_active', $category->is_active ?? true)" />

</div>
