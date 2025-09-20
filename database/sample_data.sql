-- Sample Data for FSMC Database
USE fsmc_database;

-- Insert sample users
INSERT INTO users (first_name, last_name, email, password, role, status, phone, address) VALUES
('John', 'Doe', 'john.doe@fsmc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Active', '+250788123456', 'Kigali, Rwanda'),
('Jane', 'Smith', 'jane.smith@fsmc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff', 'Active', '+250788234567', 'Kigali, Rwanda'),
('David', 'Miller', 'david.miller@fsmc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff', 'Active', '+250788345678', 'Kigali, Rwanda'),
('Sarah', 'Green', 'sarah.green@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User', 'Active', '+250788456789', 'Kigali, Rwanda'),
('Lisa', 'Taylor', 'lisa.taylor@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User', 'Active', '+250788567890', 'Kigali, Rwanda');

-- Insert product categories
INSERT INTO product_categories (name, description, status) VALUES
('GPS Equipment', 'Global Positioning System devices and accessories', 'Active'),
('Surveying Instruments', 'Professional surveying tools and equipment', 'Active'),
('Mapping Software', 'GIS and mapping software solutions', 'Active'),
('Drones & UAV', 'Unmanned aerial vehicles for surveying and mapping', 'Active'),
('Accessories', 'Supporting equipment and accessories', 'Active');

-- Insert products
INSERT INTO products (name, description, category_id, price, stock_quantity, manufacturer, model, warranty, support_info, status, featured) VALUES
('GPS Receiver Pro X4', 'High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.', 1, 3500.00, 15, 'Trimble', 'SX10', '2 Years', '24/7 Technical Support', 'Active', TRUE),
('RTK GPS System', 'Real-time kinematic GPS system for centimeter-level positioning accuracy. Perfect for detailed mapping and staking applications.', 1, 4200.00, 8, 'Leica Geosystems', 'GS18 T', '3 Years', 'On-site Support Available', 'Active', TRUE),
('Total Station Professional', 'Advanced total station with integrated imaging and scanning capabilities for comprehensive surveying solutions.', 2, 5800.00, 5, 'Topcon', 'GT-1200', '2 Years', 'Professional Training Included', 'Active', FALSE),
('Digital Level System', 'Precise digital leveling instrument for elevation measurements and construction grade work.', 2, 2100.00, 12, 'Sokkia', 'SDL1X', '1 Year', 'Standard Support', 'Active', FALSE),
('Survey Drone Kit', 'Complete UAV mapping solution with high-resolution camera and photogrammetry software.', 4, 8500.00, 3, 'DJI', 'Phantom 4 RTK', '1 Year', 'Training and Support Package', 'Active', TRUE);

-- Insert service categories
INSERT INTO service_categories (name, description, status) VALUES
('Surveying', 'Land and property surveying services', 'Active'),
('Engineering', 'Engineering surveying and infrastructure services', 'Active'),
('Technology', 'GPS, GIS and technology-based services', 'Active'),
('Mapping', 'Cartographic and mapping services', 'Active'),
('Consulting', 'Professional consulting and advisory services', 'Active');

-- Insert services
INSERT INTO services (name, description, category_id, price, duration, status, featured) VALUES
('Land Surveying & Mapping', 'Precise land measurement and mapping services for property boundaries, topographic surveys, and construction layout.', 1, 500.00, '3-5 days', 'Active', TRUE),
('Cadastral Surveying', 'Professional cadastral surveying for property boundaries, subdivisions, and land registration purposes.', 1, 750.00, '5-7 days', 'Active', TRUE),
('Infrastructure Surveying', 'Surveying services for infrastructure projects including roads, bridges, utilities, and construction sites.', 2, 1200.00, '1-2 weeks', 'Active', FALSE),
('GPS/GNSS Services', 'High-precision GPS and GNSS surveying solutions for accurate positioning and navigation applications.', 3, 800.00, '2-4 days', 'Active', TRUE),
('GIS Mapping & Analysis', 'Geographic Information System mapping, spatial analysis, and data management services.', 4, 900.00, '1-2 weeks', 'Active', FALSE),
('Topographic Mapping', 'Detailed topographic mapping services for engineering, planning, and development projects.', 4, 650.00, '5-10 days', 'Active', FALSE);

-- Insert training categories
INSERT INTO training_categories (name, description, status) VALUES
('GPS Training', 'Global Positioning System operation and applications', 'Active'),
('Mapping Techniques', 'Traditional and modern mapping methodologies', 'Active'),
('Software Training', 'GIS and surveying software applications', 'Active'),
('Surveying Fundamentals', 'Basic surveying principles and practices', 'Active'),
('Advanced Techniques', 'Specialized and advanced surveying methods', 'Active');

-- Insert training programs
INSERT INTO training_programs (title, description, category_id, duration, price, max_enrollment, current_enrollment, start_date, end_date, status, language, level) VALUES
('Advanced GPS for Surveyors', 'Comprehensive training on GPS technology, RTK systems, and precision positioning for professional surveyors.', 1, '3 days', 1200.00, 20, 15, '2024-06-15', '2024-06-17', 'Active', 'English, French', 'Advanced'),
('Modern Mapping Techniques', 'Learn contemporary mapping methods including digital cartography, remote sensing, and GIS integration.', 2, '5 days', 1750.00, 15, 8, '2024-07-10', '2024-07-14', 'Active', 'English, French', 'Intermediate'),
('GIS Software Masterclass', 'Intensive training on QGIS, ArcGIS, and other professional GIS software applications.', 3, '4 days', 1500.00, 15, 3, '2024-08-05', '2024-08-08', 'Active', 'English', 'Intermediate'),
('Total Station Operation', 'Hands-on training for total station setup, operation, and data processing for field surveyors.', 4, '2 days', 800.00, 25, 18, '2024-09-20', '2024-09-21', 'Active', 'English, French', 'Beginner'),
('Drone Surveying & Mapping', 'Learn UAV operations, photogrammetry, and aerial mapping techniques for modern surveying.', 5, '3 days', 2000.00, 12, 7, '2024-10-15', '2024-10-17', 'Active', 'English', 'Advanced');

-- Insert sample research categories
INSERT INTO research_categories (name, description, status) VALUES
('Remote Sensing', 'Research related to satellite imagery and remote sensing technologies', 'Active'),
('Mapping', 'Research focused on mapping techniques and technologies', 'Active'),
('Surveying', 'Research in surveying methodologies and land administration', 'Active'),
('GIS Technology', 'Research in Geographic Information Systems and spatial analysis', 'Active');

-- Insert sample training categories
INSERT INTO training_categories (name, description, status) VALUES
('GPS Training', 'Training programs focused on GPS technology and applications', 'Active'),
('Mapping Techniques', 'Training in various mapping and cartographic techniques', 'Active'),
('Software Training', 'Training on surveying and mapping software applications', 'Active'),
('Field Procedures', 'Training on field surveying procedures and best practices', 'Active');

-- Insert research projects (using category_id references)
INSERT INTO research_projects (title, description, category_id, investigators, start_date, end_date, status, research_id, findings) VALUES
('GIS Applications in Urban Planning', 'Comprehensive study on the integration of Geographic Information Systems in urban planning and development processes in Rwanda.', 4, 'Dr. John Smith, Dr. Jane Doe', '2023-01-15', '2023-08-30', 'Completed', 'RES-2023-001', 'GIS technology significantly improves urban planning efficiency and decision-making processes.'),
('Satellite-based Land Use Monitoring', 'Research on using satellite imagery and remote sensing for monitoring land use changes and environmental impact assessment.', 1, 'Dr. David Miller, Dr. Sarah Green', '2023-03-01', '2024-02-28', 'In Progress', 'RES-2023-002', NULL),
('Precision Agriculture Mapping', 'Development of precision agriculture techniques using GPS and GIS technology for improved crop management.', 2, 'Dr. Lisa Taylor, Dr. John Smith', '2023-06-01', '2024-05-31', 'In Progress', 'RES-2023-003', NULL),
('Digital Cadastral System Implementation', 'Study on implementing digital cadastral systems for improved land administration and property management.', 3, 'Dr. Jane Doe, Dr. David Miller', '2023-09-01', '2024-08-31', 'In Progress', 'RES-2023-004', NULL);

-- Insert sample messages
INSERT INTO messages (subject, message, sender_name, sender_email, type, status, priority) VALUES
('Training Program Inquiry', 'I am interested in enrolling in the Advanced GPS for Surveyors training program. Could you provide more details about the curriculum and prerequisites?', 'Michael Johnson', 'michael.johnson@email.com', 'Email', 'Unread', 'Normal'),
('Equipment Purchase Request', 'We need a quote for 3 units of GPS Receiver Pro X4 for our upcoming project. Please include delivery timeline and warranty information.', 'Construction Corp Ltd', 'procurement@constructioncorp.com', 'Email', 'Read', 'High'),
('Research Collaboration Proposal', 'Our university would like to collaborate on the Digital Cadastral System Implementation research project. Please contact us to discuss potential partnership opportunities.', 'Prof. Robert Wilson', 'r.wilson@university.edu', 'Email', 'Replied', 'High'),
('Service Feedback', 'Thank you for the excellent cadastral surveying service provided for our property development project. The team was professional and delivered accurate results on time.', 'Property Developers Inc', 'feedback@propertydev.com', 'Email', 'Read', 'Low');

-- Insert contact form submissions
INSERT INTO contact_submissions (name, email, phone, service_interested, message, status) VALUES
('Alice Brown', 'alice.brown@email.com', '+250788111222', 'Land Surveying', 'I need a topographic survey for a 2-hectare plot in Kigali. Please provide a quote and timeline.', 'New'),
('Peter Uwimana', 'peter.uwimana@email.com', '+250788333444', 'GPS/GNSS Services', 'Looking for GPS surveying services for a road construction project. Need high precision measurements.', 'In Progress'),
('Marie Mukamana', 'marie.mukamana@email.com', '+250788555666', 'Training', 'Interested in GIS training programs. Do you offer customized training for government employees?', 'Resolved'),
('James Nkurunziza', 'james.nkurunziza@email.com', '+250788777888', 'Infrastructure Surveying', 'Need surveying services for a bridge construction project. Please contact me for project details.', 'New');

-- Update training enrollment counts
UPDATE training_programs SET current_enrollment = 15 WHERE id = 1;
UPDATE training_programs SET current_enrollment = 8 WHERE id = 2;
UPDATE training_programs SET current_enrollment = 3 WHERE id = 3;
UPDATE training_programs SET current_enrollment = 18 WHERE id = 4;
UPDATE training_programs SET current_enrollment = 7 WHERE id = 5;
