import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import { fetchProducts } from "../API/GET/GetProduct";

interface ProductState {
    products: any[]; // Bạn có thể thay 'any[]' bằng kiểu thực tế của sản phẩm nếu biết
    loading: boolean;
    error: string | null; // Cho phép null cho trường hợp không có lỗi
}

const initialState: ProductState = {
    products: [],
    loading: false,
    error: null,
};

const productsSlice = createSlice({
    name: 'products',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchProducts.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(fetchProducts.fulfilled, (state, action: PayloadAction<any[]>) => {
                state.loading = false;
                state.products = action.payload;
            })
            .addCase(fetchProducts.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message ?? null; // Xử lý nếu `message` là undefined
            });
    },
});

// Các selector với kiểu dữ liệu rõ ràng
export const selectProducts = (state: { products: ProductState }) => state.products.products;
export const selectLoading = (state: { products: ProductState }) => state.products.loading;
export const selectError = (state: { products: ProductState }) => state.products.error;

export default productsSlice.reducer;
