import { createAsyncThunk, createSlice, PayloadAction } from "@reduxjs/toolkit";
import axios from "axios";

export interface Voucher {
    id: number;
    code: string;
    type: number;
    discount_value: number;
    description: string;
    discount_min: number;
    max_discount: number; 
    min_order_count: number; 
    max_order_count: number; 
    quantity: number; 
    used_times: number; 
    start_day: string; 
    end_day: string;
    status: number; 
  }

  interface VoucherState {
    vouchers: Voucher[];
    loading: boolean;
    error: string | null;
  }
  
  const initialState: VoucherState = {
    vouchers: [],
    loading: false,
    error: null,
  };

  export const fetchVouchers = createAsyncThunk(
    'vouchers/fetchVouchers',
    async (_, { rejectWithValue }) => {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/vouchers');
        return response.data; 
      } catch (error: any) {
        return rejectWithValue(error.response.data); 
    }
}
  );
  
  const VoucherReducer = createSlice({
    name: 'voucher',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
      builder
        .addCase(fetchVouchers.pending, (state) => {
          state.loading = true;
          state.error = null;
        })
        .addCase(fetchVouchers.fulfilled, (state, action: PayloadAction<Voucher[]>) => {
          state.loading = false;
          state.vouchers = action.payload;
        })
        .addCase(fetchVouchers.rejected, (state, action) => {
          state.loading = false;
          state.error = action.payload as string;
        });
    },
  });
  
  export default VoucherReducer.reducer;