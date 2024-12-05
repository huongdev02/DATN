import BannerComponent from "../../Component/HomeComponent/BannerComponent"
import CategoriesSlider from "../../Component/HomeComponent/CategoriesSlider"
import New from "../../Component/HomeComponent/New"
import NewProduct from "../../Component/HomeComponent/NewProducts"
import ProductWithCategories from "../../Component/HomeComponent/ProductWithCategories"
import ShopByCategory from "../../Component/HomeComponent/ShopByCategory"
import TrendingProduct from "../../Component/HomeComponent/TrendingProduct"

const Home:React.FC = () => {
    return (
        <>
        <main className="main">
        <BannerComponent />
        {/* <TrendingProduct /> */}
        {/* <NewProduct/>
        <CategoriesSlider /> */}
        <ProductWithCategories />
        {/* <ShopByCategory /> */}
        <New />
        </main>
        </>
    )
}

export default Home