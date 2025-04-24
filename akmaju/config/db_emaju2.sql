CREATE TABLE `users`(
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(255) NOT NULL,
    `user_email` VARCHAR(255) NOT NULL,
    `user_ic` VARCHAR(12) NOT NULL,
    `user_password` VARCHAR(255) NULL,
    `user_role` INT(11) NOT NULL DEFAULT '1' COMMENT '1:Admin, 2:User',
    `user_status` INT(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
    `user_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `user_email` (`user_email`),
    UNIQUE KEY `user_ic` (`user_ic`)
);

CREATE TABLE `states`(
    `state_id` INT(11) NOT NULL AUTO_INCREMENT,
    `state_name` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`state_id`)
);

INSERT INTO
    `states` (`state_id`, `state_name`)
VALUES
    (1, 'Johor'),
    (2, 'Kedah'),
    (3, 'Kelantan'),
    (4, 'Melaka'),
    (5, 'Negeri Sembilan'),
    (6, 'Pahang'),
    (7, 'Perak'),
    (8, 'Perlis'),
    (9, 'Pulau Pinang'),
    (10, 'Sabah'),
    (11, 'Sarawak'),
    (12, 'Selangor'),
    (13, 'Terengganu'),
    (14, 'Wilayah Persekutuan Kuala Lumpur'),
    (15, 'Wilayah Persekutuan Labuan'),
    (16, 'Wilayah Persekutuan Putrajaya');

CREATE TABLE `customers`(
    `customer_id` INT(11) NOT NULL AUTO_INCREMENT,
    `customer_user_id` INT(11) NOT NULL,
    `customer_phone` int(11) NOT NULL,
    `customer_type` VARCHAR(255) NOT NULL,
    `customer_address` VARCHAR(255) NULL,
    `customer_city` VARCHAR(255) NULL,
    `customer_state_id` INT(11) NULL,
    `customer_postcode` VARCHAR(5) NULL,
    `customer_country` VARCHAR(255) NULL DEFAULT 'Malaysia',
    `customer_status` INT(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
    `customer_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `customer_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`customer_id`),
    FOREIGN KEY (`customer_state_id`) REFERENCES `states` (`state_id`),
    FOREIGN KEY (`customer_user_id`) REFERENCES `users` (`user_id`)
);

-- dummy user admin
INSERT INTO
    `users` (
        `user_name`,
        `user_email`,
        `user_ic`,
        `user_password`,
        `user_role`,
        `user_status`
    )
VALUES
    (
        'MUHAMMAD SHAZWAN DANIAL BIN YOUHARDY',
        'developerizwan27@gmail.com',
        '990808025645',
        '$2y$10$/a/O6RMb4MlNnnqjMX.yvOkjOe7Kzj8GudAbecpELF1mMn9xfto5i',
        1,
        1
    );

-- Password admin: @Admin123
CREATE TABLE `product_category`(
    `product_category_id` INT(11) NOT NULL AUTO_INCREMENT,
    `product_category_name` VARCHAR(30) NOT NULL,
    `product_category_status` INT(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
    `product_category_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `product_category_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_category_id`)
);

INSERT INTO
    `product_category` (`product_category_name`)
VALUES
    ('Advertisement'),
    ('Construction');

CREATE TABLE `products`(
    `product_id` INT(11) NOT NULL AUTO_INCREMENT,
    `product_category_id` INT(11) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_description` TEXT NULL,
    `product_cost_price` DECIMAL(10, 2) NOT NULL,
    `product_selling_price` DECIMAL(10, 2) NOT NULL,
    `product_tax_code` VARCHAR(255) NOT NULL,
    `product_tax_amount` DECIMAL(10, 2) NOT NULL,
    `product_discount_percent` DECIMAL(10, 2) NOT NULL,
    `product_discount_amount` DECIMAL(10, 2) NOT NULL,
    `product_quantity` INT(11) NOT NULL,
    `product_status` INT(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
    `product_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `product_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_id`),
    FOREIGN KEY (`product_category_id`) REFERENCES `product_category` (`product_category_id`)
);

CREATE TABLE `quotations` (
    `quotation_id` int(11) NOT NULL AUTO_INCREMENT,
    `quotation_customer_id` int(11) NOT NULL,
    `quotation_date` datetime NOT NULL DEFAULT current_timestamp(),
    `quotation_status` int(11) NOT NULL DEFAULT 1,
    `quotation_created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `quotation_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `quotation_deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`quotation_id`),
    FOREIGN KEY (`quotation_customer_id`) REFERENCES `customers` (`customer_id`)
);

CREATE TABLE `quotation_details` (
    `quotation_detail_id` int(11) NOT NULL AUTO_INCREMENT,
    `quotation_detail_quotation_id` int(11) NOT NULL,
    `quotation_detail_product_id` int(11) NOT NULL,
    `quotation_detail_quantity` int(11) NOT NULL,
    `quotation_detail_selling_price` double NOT NULL,
    `quotation_detail_discount_percent` double NOT NULL,
    `quotation_detail_discount_amount` double NOT NULL,
    `quotation_detail_tax_code` varchar(255) NOT NULL,
    `quotation_detail_total` double NOT NULL,
    PRIMARY KEY (`quotation_detail_id`),
    FOREIGN KEY (`quotation_detail_quotation_id`) REFERENCES `quotations` (`quotation_id`),
    FOREIGN KEY (`quotation_detail_product_id`) REFERENCES `products` (`product_id`)
);

CREATE TABLE `invoices` (
    `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
    `invoice_quotation_id` int(11) NOT NULL,
    `invoice_status` int(11) NOT NULL DEFAULT 1,
    `invoice_payment_method` varchar(255) NOT NULL,
    `invoice_payment_delivery_fee` decimal(10, 2) NOT NULL,
    `invoice_payment_status` int(11) NOT NULL DEFAULT 0,
    `invoice_payment_created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `invoice_payment_updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `invoice_deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`invoice_id`),
    FOREIGN KEY (`invoice_quotation_id`) REFERENCES `quotations` (`quotation_id`)
);

CREATE TABLE `password_resets` (
    `password_reset_id` int(11) NOT NULL AUTO_INCREMENT,
    `password_reset_user_id` int(11) NOT NULL,
    `password_reset_token` varchar(255) NOT NULL,
    `password_reset_status` int(11) NOT NULL DEFAULT 1,
    `password_reset_created_at` datetime NOT NULL,
    PRIMARY KEY (`password_reset_id`),
    FOREIGN KEY (`password_reset_user_id`) REFERENCES `users` (`user_id`)
)