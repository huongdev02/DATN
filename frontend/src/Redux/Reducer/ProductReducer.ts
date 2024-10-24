import { createSlice } from "@reduxjs/toolkit";
import { fetchProducts } from "../API/GET/GetProduct";

const productsSlice = createSlice({
    name: 'products',
    initialState: {
        products: [],
        loading: false,
        error: null,
    },
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchProducts.pending, (state) => {
                state.loading = true; // Đang tải
                state.error = null; // Không có lỗi
            })
            .addCase(fetchProducts.fulfilled, (state, action) => {
                state.loading = false; // Đã tải xong
                state.products = action.payload; // Lưu dữ liệu sản phẩm
            })
            .addCase(fetchProducts.rejected, (state, action) => {
                state.loading = false; // Đã tải xong
                state.error = action.error.message; // Lưu lỗi nếu có
            });
    },
});

// Xuất các selector và reducer
export const selectProducts = (state) => state.products.products;
export const selectLoading = (state) => state.products.loading;
export const selectError = (state) => state.products.error;

export default productsSlice.reducer;