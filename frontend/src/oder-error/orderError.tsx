
import { DeleteOutlined, CheckOutlined, CloseOutlined } from "@ant-design/icons";
import './orderError.css'
function OrderError() {
  return (
    <>
      <section className="thank">
        <div className="thanks">
          <div style={{display:'flex', justifyContent:'center', marginTop:'60px'}}>
            <div className="icon-thank">
              <CloseOutlined
                style={{ fontSize: "30px", color: "red" }}
              />
            </div>
          </div>
          <span className="text-succsess">Thanh toán thất bại</span>
          <p className="text-thank">Đơn hàng của quý khách đã bị hủy !</p>
        </div>
      </section>
    </>
  );
}
export default OrderError;