
import { configureStore } from '@reduxjs/toolkit';
import { useDispatch } from 'react-redux';
import ProductReducer from '../Redux/Reducer/ProductReducer'
import CartReducer from '../Redux/Reducer/CartReducer'
import PaymentMethodReducer from './Reducer/PaymentMethodReducer'
import ShipAddressReducer from './Reducer/ShipAddressReducer'
import OrderReducer from './Reducer/OrderReducer'
import CategoryReducer from './Reducer/CategoriesReducer'
import AuthReducer from './Reducer/AuthReducer'
export const store = configureStore({
    reducer: {
        products: ProductReducer,
        cart: CartReducer,
        paymentMethod: PaymentMethodReducer,
        shipAdress: ShipAddressReducer,
        order: OrderReducer,
        categories: CategoryReducer,
        auth: AuthReducer,
    },
});

export type RootState = ReturnType<typeof store.getState>

export type AppDispatch = typeof store.dispatch

export const useAppDispatch = () => useDispatch<AppDispatch>()

export default store
