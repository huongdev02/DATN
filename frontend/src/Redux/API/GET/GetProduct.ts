import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';

// Tạo một Async Thunk để gọi API
export const fetchProducts = createAsyncThunk('products/fetchProducts', async () => {
    const response = await fetch('http://127.0.0.1:8000/api/products'); 
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
});