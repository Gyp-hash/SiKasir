@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="category_id">Kategori</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="name">Nama Produk</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="selling_price">Harga Jual</label>
        <input class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" type="number" min="0" step="100" value="{{ old('selling_price', $product->selling_price ?? '') }}" required>
        @error('selling_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="capital_price">Harga Modal</label>
        <input class="form-control @error('capital_price') is-invalid @enderror" id="capital_price" name="capital_price" type="number" min="0" step="100" value="{{ old('capital_price', $product->capital_price ?? '') }}" required>
        @error('capital_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="stock">Stok</label>
        <input class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="minimum_stock">Minimum Stok</label>
        <input class="form-control @error('minimum_stock') is-invalid @enderror" id="minimum_stock" name="minimum_stock" type="number" min="0" value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}" required>
        @error('minimum_stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $product->status ?? '') === 'inactive')>Nonaktif</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="image">Gambar Produk</label>
    <input class="form-control @error('image') is-invalid @enderror" id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    <div class="form-text">Format: JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if (isset($product) && $product->image_url)
        <div class="mt-3">
            <img class="rounded border" src="{{ $product->image_url }}" alt="{{ $product->name }}" width="120" height="120" style="object-fit: cover;">
        </div>
    @endif
</div>
