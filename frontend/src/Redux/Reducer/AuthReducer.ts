import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';

// Define an interface for the initial state
interface AuthState {
  user: { id: string; username: string; email: string; role: string } | null;
  loading: boolean;
  error: string | null;
}

// Initial state
const initialState: AuthState = {
  user: null,
  loading: false,
  error: null,
};

// Create async thunk for login
export const login = createAsyncThunk('auth/login', async (credentials: { username: string; password: string }) => {
  const response = await fetch('http://yourapi.com/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(credentials),
  });

  if (!response.ok) {
    throw new Error('Login failed');
  }

  const data = await response.json();
  return data; // data should include id, username, email, role
});

// Create async thunk for logout
export const logout = createAsyncThunk('auth/logout', async () => {
  // Call your logout API if needed
  return null;
});

// Create the slice
const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    clearError: (state) => {
      state.error = null;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(login.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(login.fulfilled, (state, action) => {
        state.loading = false;
        state.user = action.payload; // Save user data
        localStorage.setItem('user', JSON.stringify(action.payload)); // Save user data in localStorage
      })
      .addCase(login.rejected, (state, action) => {
        state.loading = false;
        state.error = action.error.message || 'Login failed';
      })
      .addCase(logout.fulfilled, (state) => {
        state.user = null; // Clear user data on logout
        localStorage.removeItem('user'); // Remove user data from localStorage
      });
  },
});

// Export actions and reducer
export const { clearError } = authSlice.actions;
export default authSlice.reducer;
