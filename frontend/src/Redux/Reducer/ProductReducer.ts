// src/redux/productSlice.ts
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';

interface Colors {
    id: string;
    name_color: string;
    hex_color: string;
}

interface Gallery {
    id: number;
    product_id: number;
    image_path: string;
}

interface Sizes {
    id: string;
    size: string;
}

export interface Product {
    id: string;
    name: string;
    import_price: number;
    price: number;
    description: string;
    avatar: string
    colors: Colors[];
    sizes: Sizes[];
    galleries: Gallery[];
}

interface ProductState {
    products: Product[];
    loading: boolean;
    error: string | null;
}

const initialState: ProductState = {
    products: [],
    loading: false,
    error: null,
};

export const fetchProducts = createAsyncThunk('products/fetchProducts', async () => {
    const response = await fetch('http://127.0.0.1:8000/api/products');
    if (!response.ok) {
        throw new Error('Failed to fetch products');
    }
    const data = await response.json();
    return data;
});

const ProductReducer = createSlice({
    name: 'products',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchProducts.pending, (state) => {
                state.loading = true;
            })
            .addCase(fetchProducts.fulfilled, (state, action) => {
                state.loading = false;
                state.products = action.payload;
            })
            .addCase(fetchProducts.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message || 'Something went wrong';
            });
    },
});

export default ProductReducer.reducer;
