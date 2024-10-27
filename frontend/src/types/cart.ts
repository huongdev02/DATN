export type Cart = {
    user_id: number;
    product_id: number;
    quantity: number;
    total: number;
  };
export type Category = {
    id: string;
    name: string;
  };
export type IProduct = {
    id: string;
    name: string;
    avatar: string;
    category_id: number;
    import_price: number;
    price: number;
    description: string;
    display: number;
    status: number;
    created_at: string;
    updated_at: string;
  };
  export type UserCart = {
    id: string;
    usename: string;
    fullname: string;

  };


  
  