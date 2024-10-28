// shipAddressSlice.ts
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

export interface ShipAddress {
    recipient_name: string;
    email: string;
    phone_number: string;
    ship_address: string;
    user_id?: string | null;
}

interface ShipAddressState {
    loading: boolean;
    shipAddress: ShipAddress | null;
    error: string | null;
}

const initialState: ShipAddressState = {
    loading: false,
    shipAddress: null,
    error: null,
};

export const postShipAddress = createAsyncThunk(
    'shipAddress/post',
    async (data: { recipient_name: string; email: string; phone_number: string; ship_address: string }) => {
        const response = await axios.post('http://127.0.0.1:8000/api/ship_addresses', data);
        return response.data;
    }
);

const ShipAddressReducer = createSlice({
    name: 'shipAddress',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(postShipAddress.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(postShipAddress.fulfilled, (state, action) => {
                state.loading = false;
                state.shipAddress = action.payload;
            })
            .addCase(postShipAddress.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message || 'Something went wrong!';
            });
    },
});

// Xuất reducer và action (nếu cần)
export default ShipAddressReducer.reducer;
