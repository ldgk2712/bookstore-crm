USE bookstore_crm;

-- Tài khoản demo: admin@example.com / 123456
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@example.com', '$2b$10$TBcFk3X74Bz9VfvjC3dKKulF5Figu27xD/SBeEboO9Au32Jyo0Fpi', 'admin');

-- Seed 25 customers (>= 20 dòng để test pagination)
INSERT INTO customers (name, email, phone, book_interest, status, note) VALUES
('Anna Nguyen', 'anna.nguyen1@example.com', '0909000001', 'Van hoc', 'contacted', 'Khach hang seed #1'),
('Ben Tran', 'ben.tran2@example.com', '0909000002', 'Van hoc', 'converted', 'Khach hang seed #2'),
('Chi Le', 'chi.le3@example.com', '0909000003', 'Van hoc', 'closed', 'Khach hang seed #3'),
('Duyen Pham', 'duyen.pham4@example.com', '0909000004', 'Van hoc', 'new', 'Khach hang seed #4'),
('Minh Ho', 'minh.ho5@example.com', '0909000005', 'Van hoc', 'contacted', 'Khach hang seed #5'),
('Khanh Vo', 'khanh.vo6@example.com', '0909000006', 'Van hoc', 'converted', 'Khach hang seed #6'),
('Lan Bui', 'lan.bui7@example.com', '0909000007', 'Van hoc', 'closed', 'Khach hang seed #7'),
('Hoang Dang', 'hoang.dang8@example.com', '0909000008', 'Van hoc', 'new', 'Khach hang seed #8'),
('Trang Do', 'trang.do9@example.com', '0909000009', 'Van hoc', 'contacted', 'Khach hang seed #9'),
('Phong Ly', 'phong.ly10@example.com', '0909000010', 'Van hoc', 'converted', 'Khach hang seed #10'),
('Yen Vu', 'yen.vu11@example.com', '0909000011', 'Van hoc', 'closed', 'Khach hang seed #11'),
('Quan Nguyen', 'quan.nguyen12@example.com', '0909000012', 'Van hoc', 'new', 'Khach hang seed #12'),
('Thao Tran', 'thao.tran13@example.com', '0909000013', 'Van hoc', 'contacted', 'Khach hang seed #13'),
('Duc Le', 'duc.le14@example.com', '0909000014', 'Van hoc', 'converted', 'Khach hang seed #14'),
('Mai Pham', 'mai.pham15@example.com', '0909000015', 'Van hoc', 'closed', 'Khach hang seed #15'),
('Son Ho', 'son.ho16@example.com', '0909000016', 'Van hoc', 'new', 'Khach hang seed #16'),
('Hanh Vo', 'hanh.vo17@example.com', '0909000017', 'Van hoc', 'contacted', 'Khach hang seed #17'),
('Tam Bui', 'tam.bui18@example.com', '0909000018', 'Van hoc', 'converted', 'Khach hang seed #18'),
('Kien Dang', 'kien.dang19@example.com', '0909000019', 'Van hoc', 'closed', 'Khach hang seed #19'),
('Vy Do', 'vy.do20@example.com', '0909000020', 'Van hoc', 'new', 'Khach hang seed #20'),
('Long Ly', 'long.ly21@example.com', '0909000021', 'Van hoc', 'contacted', 'Khach hang seed #21'),
('Nga Vu', 'nga.vu22@example.com', '0909000022', 'Van hoc', 'converted', 'Khach hang seed #22'),
('Huy Nguyen', 'huy.nguyen23@example.com', '0909000023', 'Van hoc', 'closed', 'Khach hang seed #23'),
('Linh Tran', 'linh.tran24@example.com', '0909000024', 'Van hoc', 'new', 'Khach hang seed #24'),
('Dat Le', 'dat.le25@example.com', '0909000025', 'Van hoc', 'contacted', 'Khach hang seed #25');

-- Seed 25 book orders (>= 20 dòng để test pagination)
INSERT INTO book_orders (order_code, customer_name, customer_email, book_title, quantity, total_amount, status) VALUES
('ORD-2026-0001', 'Anna Nguyen', 'anna.nguyen1@example.com', 'Sach 1', 2, 50000, 'paid'),
('ORD-2026-0002', 'Ben Tran', 'ben.tran2@example.com', 'Sach 2', 3, 100000, 'shipping'),
('ORD-2026-0003', 'Chi Le', 'chi.le3@example.com', 'Sach 3', 4, 150000, 'completed'),
('ORD-2026-0004', 'Duyen Pham', 'duyen.pham4@example.com', 'Sach 4', 5, 200000, 'cancelled'),
('ORD-2026-0005', 'Minh Ho', 'minh.ho5@example.com', 'Sach 5', 1, 250000, 'pending'),
('ORD-2026-0006', 'Khanh Vo', 'khanh.vo6@example.com', 'Sach 6', 2, 300000, 'paid'),
('ORD-2026-0007', 'Lan Bui', 'lan.bui7@example.com', 'Sach 7', 3, 350000, 'shipping'),
('ORD-2026-0008', 'Hoang Dang', 'hoang.dang8@example.com', 'Sach 8', 4, 400000, 'completed'),
('ORD-2026-0009', 'Trang Do', 'trang.do9@example.com', 'Sach 9', 5, 450000, 'cancelled'),
('ORD-2026-0010', 'Phong Ly', 'phong.ly10@example.com', 'Sach 10', 1, 500000, 'pending'),
('ORD-2026-0011', 'Yen Vu', 'yen.vu11@example.com', 'Sach 11', 2, 550000, 'paid'),
('ORD-2026-0012', 'Quan Nguyen', 'quan.nguyen12@example.com', 'Sach 12', 3, 600000, 'shipping'),
('ORD-2026-0013', 'Thao Tran', 'thao.tran13@example.com', 'Sach 13', 4, 650000, 'completed'),
('ORD-2026-0014', 'Duc Le', 'duc.le14@example.com', 'Sach 14', 5, 700000, 'cancelled'),
('ORD-2026-0015', 'Mai Pham', 'mai.pham15@example.com', 'Sach 15', 1, 750000, 'pending'),
('ORD-2026-0016', 'Son Ho', 'son.ho16@example.com', 'Sach 16', 2, 800000, 'paid'),
('ORD-2026-0017', 'Hanh Vo', 'hanh.vo17@example.com', 'Sach 17', 3, 850000, 'shipping'),
('ORD-2026-0018', 'Tam Bui', 'tam.bui18@example.com', 'Sach 18', 4, 900000, 'completed'),
('ORD-2026-0019', 'Kien Dang', 'kien.dang19@example.com', 'Sach 19', 5, 950000, 'cancelled'),
('ORD-2026-0020', 'Vy Do', 'vy.do20@example.com', 'Sach 20', 1, 1000000, 'pending'),
('ORD-2026-0021', 'Long Ly', 'long.ly21@example.com', 'Sach 21', 2, 1050000, 'paid'),
('ORD-2026-0022', 'Nga Vu', 'nga.vu22@example.com', 'Sach 22', 3, 1100000, 'shipping'),
('ORD-2026-0023', 'Huy Nguyen', 'huy.nguyen23@example.com', 'Sach 23', 4, 1150000, 'completed'),
('ORD-2026-0024', 'Linh Tran', 'linh.tran24@example.com', 'Sach 24', 5, 1200000, 'cancelled'),
('ORD-2026-0025', 'Dat Le', 'dat.le25@example.com', 'Sach 25', 1, 1250000, 'pending');
