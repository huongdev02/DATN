   <!-- Thống kê doanh thu -->

   <div class="card text-white bg-success h-100">
       <div class="card-body">
           <h5 class="card-title">Tổng Doanh Thu</h5>
           <p class="card-text">{{ number_format($order['total_amount'], 0, ',', '.') }} VNĐ</p>
           <p class="card-text">
               So với kỳ trước:
               <span class="font-weight-bold">{{ $order['change'] }}</span>
               @if ($order['change'] > 0)
                   <i class="fas fa-arrow-up text-success"></i>
               @elseif($order['change'] < 0)
                   <i class="fas fa-arrow-down text-danger"></i>
               @else
                   <i class="fas fa-arrow-right text-muted"></i>
               @endif
           </p>
       </div>

   </div>

   <!-- Thống kê đơn hàng -->

   <div class="card text-white bg-info h-100">
       <div class="card-body">
           <h5 class="card-title">Số lượng đơn hoàn thành</h5>
           <p class="card-text">{{ $order['order_count'] }} đơn</p>
           <p class="card-text">
               So với kỳ trước:
               <span class="font-weight-bold">{{ $order['order_count_change'] }}</span>
               @if ($order['order_count_change'] > 0)
                   <i class="fas fa-arrow-up text-success"></i>
               @elseif($order['order_count_change'] < 0)
                   <i class="fas fa-arrow-down text-danger"></i>
               @else
                   <i class="fas fa-arrow-right text-muted"></i>
               @endif
           </p>
       </div>
   </div>
