import BannerComponent from "../../Component/HomeComponent/BannerComponent"
import CategoriesSlider from "../../Component/HomeComponent/CategoriesSlider"
import New from "../../Component/HomeComponent/New"
import ProductWithCategories from "../../Component/HomeComponent/ProductWithCategories"
import ShopByCategory from "../../Component/HomeComponent/ShopByCategory"
import TrendingProduct from "../../Component/HomeComponent/TrendingProduct"
import NewProduct from "../../Component/HomeComponent/NewProducts"

const Home:React.FC = () => {
    return (
        <>
        <main className="main">
        <BannerComponent />
        <ProductWithCategories />
        <TrendingProduct />
        <NewProduct/>
        {/* <CategoriesSlider /> */}
        {/* <ShopByCategory /> */}
        <New />
        </main>
        </>
    )
}

export default Home