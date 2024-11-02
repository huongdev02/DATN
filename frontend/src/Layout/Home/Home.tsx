import BannerComponent from "../../Component/HomeComponent/BannerComponent"
import CategoriesSlider from "../../Component/HomeComponent/CategoriesSlider"
import New from "../../Component/HomeComponent/New"
import ProductWithCategories from "../../Component/HomeComponent/ProductWithCategories"
import ShopByCategory from "../../Component/HomeComponent/ShopByCategory"
import TrendingProduct from "../../Component/HomeComponent/TrendingProduct"

const Home:React.FC = () => {
    return (
        <>
        <main className="main">
        <BannerComponent />
        {/* <CategoriesSlider /> */}
        <ProductWithCategories />
        {/* <ShopByCategory /> */}
        <TrendingProduct />
        <New />
        </main>
        </>
    )
}

export default Home