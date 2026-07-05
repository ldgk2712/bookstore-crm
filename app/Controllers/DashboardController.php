<?php

class DashboardController
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private OrderRepository $orderRepository
    ) {
    }

    public function index(): void
    {
        require_login();

        render('dashboard/index', [
            'title'         => 'Dashboard',
            'totalCustomers' => $this->customerRepository->countAll(),
            'totalOrders'    => $this->orderRepository->countAll(),
            'userName'       => $_SESSION['user_name'] ?? '',
        ]);
    }
}
