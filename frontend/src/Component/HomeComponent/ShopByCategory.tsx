import React, { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import Image from '../../assets/imgs/template/icons/CategoryIcon24-1.svg';
import { RootState, useAppDispatch } from '../../Redux/store';
import { fetchCategories, fetchProductsByCategory, setActiveTab } from '../../Redux/Reducer/CategoriesReducer';

const ShopByCategory: React.FC = () => {
    const dispatch = useAppDispatch();
    const { categories, products, activeTab } = useSelector((state: RootState) => state.categories);

    useEffect(() => {
        dispatch(fetchCategories());
        dispatch(fetchProductsByCategory(1)); 
    }, [dispatch]);

    const handleCategoryClick = (categoryId: number) => {
        dispatch(setActiveTab(categoryId.toString()));
        dispatch(fetchProductsByCategory(categoryId));
    };

    return (
        <section className="section block-section-3">
            <div className="container">
                <div className="top-head">
                    <h4 className="text-uppercase brand-1 wow animate__animated animate__fadeIn">Shop by Category</h4>
                    <a className="btn btn-arrow-right wow animate__animated animate__fadeIn" href="#">View All<img src="assets/imgs/template/icons/arrow.svg" alt="Kidify" /></a>
                </div>
                <div className="row">
                    <div className="col-lg-3">
                        <div className="box-category-list mb-30">
                            <ul className="menu-category">
                                {categories.map(category => (
                                    <li key={category.id} className="wow animate__animated animate__fadeIn" data-wow-delay=".0s">
                                        <p 
                                        style={{ cursor: 'pointer' }}
                                            className={activeTab === category.id.toString() ? 'active' : ''} 
                                            onClick={() => handleCategoryClick(category.id)} 
                                        >
                                            <img src={Image} alt={category.name} /> {/* Adjust this for different category icons */}
                                            {category.name}
                                        </p>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-9">
                        <div className="row">
                            {products.map(product => (
                                <div key={product.id} className="col-lg-3 col-md-4 col-sm-6 wow animate__animated animate__fadeIn">
                                    <div className="cardProduct wow fadeInUp">
                                        <div className="cardImage">
                                            <label className="lbl-hot">hot</label>
                                            <a href={`product-single.html?id=${product.id}`}>
                                                <img className="imageMain" src={product.avatar} alt={product.name} />
                                            </a>
                                            <div className="button-select"><a href={`product-single.html?id=${product.id}`}>Add to Cart</a></div>
                                            {/* Add your quick view buttons here */}
                                        </div>
                                        <div className="cardInfo">
                                            <a href={`product-single.html?id=${product.id}`}>
                                                <h6 className="font-md-bold cardTitle">{product.name}</h6>
                                            </a>
                                            <p className="font-lg cardDesc">${product.price.toFixed(2)}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default ShopByCategory;
