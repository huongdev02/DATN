const Banner: React.FC = () => {
    return (
        <>
            <section className="section block-shop-head">
                <div className="container">
                    <h1 className="font-4xl-bold neutral-900">Baby Clothing</h1>
                    <div className="breadcrumbs">
                        <ul>
                            <li><a href="#">Home </a></li>
                            <li><a href="#">Shop</a></li>
                            <li> <a href="#">Boys Clothing</a></li>
                        </ul>
                    </div>
                    <div className="box-tags-head"> <a className="btn btn-tag" href="#">Top Rated</a><a className="btn btn-tag" href="#">Two-piece Outfits</a><a className="btn btn-tag" href="#">T-Shirts</a><a className="btn btn-tag" href="#">Boy Polo Shirts</a><a className="btn btn-tag" href="#">Boy Tanks</a><a className="btn btn-tag" href="#">Shoes</a><a className="btn btn-tag" href="#">Boys Denim</a><a className="btn btn-tag" href="#">Toddler Boys Bottoms</a><a className="btn btn-tag" href="#">Boy Swimwear</a><a className="btn btn-tag" href="#">Boys Interior</a></div>
                </div>
            </section>

        </>
    )
}

export default Banner