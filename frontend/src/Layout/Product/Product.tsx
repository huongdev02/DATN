import AsideFilter from "../../Component/ProductComponent/AsideFilter"
import Banner from "../../Component/ProductComponent/Banner"
import ProductList from "../../Component/ProductComponent/ProductList"
import ProductRelated from "../../Component/ProductComponent/RecentlyViewProduct"

const Product: React.FC = () => {
    return (
        <>
            <Banner />
            <section className="section content-products">
                <div className="container">
                    <div className="row">
                        <AsideFilter />
                        <ProductList />
                    </div>
                </div>
                <ProductRelated />
            </section >


        </>
    )
}
export default Product