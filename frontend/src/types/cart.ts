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
    avatar_url: string;
    categories: Category[];
    sizes: Size[];
    colors: Color[]
    quantity: number;
    sell_quantity?: number;
    view?: number;
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

 export interface Color {
    id: number;
    name_color: string;
    pivot: {
      product_id: number;
      color_id: number;
    };
  }
  
 export interface Size {
    id: number;
    size: string; 
    pivot: {
      product_id: number;
      size_id: number;
    };
  }


  
  