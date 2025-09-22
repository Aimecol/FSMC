-- Initial data for Fair Surveying & Mapping Company Database
-- Created: 2025-01-22

USE fsmc_db;

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, email, password_hash, full_name, role, status) VALUES
('admin', 'admin@fsmc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin', 'active');

-- Insert company settings
INSERT INTO company_settings (setting_key, setting_value, setting_type, category, description, is_public) VALUES
('company_name', 'Fair Surveying & Mapping Ltd', 'text', 'general', 'Company name', TRUE),
('company_tagline', 'Reliable | Professional | Expert Solutions', 'text', 'general', 'Company tagline', TRUE),
('company_email', 'fsamcompanyltd@gmail.com', 'text', 'contact', 'Primary email address', TRUE),
('company_phone', '+250 788 331 697', 'text', 'contact', 'Primary phone number', TRUE),
('company_address', 'Kigali, Rwanda', 'text', 'contact', 'Company address', TRUE),
('surveyor_name', 'HATANGIMANA Fulgence', 'text', 'general', 'Licensed surveyor name', TRUE),
('surveyor_code', 'LS00280', 'text', 'general', 'Surveyor license code', TRUE),
('establishment_year', '2023', 'text', 'general', 'Year company was established', TRUE),
('facebook_url', '#', 'text', 'social', 'Facebook page URL', TRUE),
('twitter_url', '#', 'text', 'social', 'Twitter profile URL', TRUE),
('linkedin_url', '#', 'text', 'social', 'LinkedIn profile URL', TRUE),
('instagram_url', '#', 'text', 'social', 'Instagram profile URL', TRUE),
('site_title', 'Fair Surveying & Mapping Ltd', 'text', 'seo', 'Default site title', FALSE),
('site_description', 'Professional surveying and mapping services in Rwanda', 'textarea', 'seo', 'Default site description', FALSE),
('admin_email_notifications', '1', 'boolean', 'admin', 'Enable email notifications for admin', FALSE),
('max_file_upload_size', '10485760', 'number', 'system', 'Maximum file upload size in bytes (10MB)', FALSE);

-- Insert default services
INSERT INTO services (title, slug, description, short_description, icon, languages, features, status, sort_order) VALUES
('First Registration', 'first-registration', 'We offer comprehensive first-time registration services for land parcels, ensuring accurate measurements and legal documentation for your property. Our team uses state-of-the-art equipment to survey your land and prepare all necessary documentation required by land registration authorities.', 'Comprehensive first-time registration services for land parcels with accurate measurements and legal documentation.', 'fas fa-file-signature', '["English", "Kinyarwanda"]', '["Accurate land measurements", "Legal documentation", "State-of-the-art equipment", "Authority compliance"]', 'active', 1),

('Land Subdivision', 'land-subdivision', 'Professional land subdivision services to help you divide your property according to legal requirements and zoning regulations. We handle all technical aspects including surveying, mapping, and documentation preparation for subdivision approval.', 'Professional land subdivision services with legal compliance and technical documentation.', 'fas fa-map-marked-alt', '["English", "Kinyarwanda"]', '["Legal compliance", "Zoning regulations", "Technical surveying", "Approval documentation"]', 'active', 2),

('House Plans', 'house-plans', 'We create detailed and code-compliant house plans tailored to your specific needs, preferences, and budget. Our designs incorporate modern architectural principles while ensuring structural integrity and optimal space utilization.', 'Detailed and code-compliant house plans with modern architectural principles.', 'fas fa-home', '["English", "Kinyarwanda"]', '["Code-compliant designs", "Modern architecture", "Structural integrity", "Space optimization"]', 'active', 3),

('Building Permits', 'building-permits', 'Complete building permit application services including document preparation, technical drawings, and liaison with local authorities to ensure smooth approval process for your construction project.', 'Complete building permit services with document preparation and authority liaison.', 'fas fa-clipboard-check', '["English", "Kinyarwanda"]', '["Document preparation", "Technical drawings", "Authority liaison", "Smooth approval process"]', 'active', 4),

('Environmental Assessment', 'environmental-assessment', 'Comprehensive environmental impact assessments for development projects, ensuring compliance with environmental regulations and sustainable development practices.', 'Environmental impact assessments ensuring regulatory compliance and sustainability.', 'fas fa-leaf', '["English", "Kinyarwanda"]', '["Impact assessment", "Regulatory compliance", "Sustainability focus", "Development support"]', 'active', 5);

-- Insert default products
INSERT INTO products (title, slug, description, short_description, category, manufacturer, model, warranty, support, icon, status, sort_order) VALUES
('Total Station Professional', 'total-station', 'High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.', 'High-precision surveying instrument for accurate field measurements.', 'equipment', 'Trimble', 'SX10', '2 Years', '24/7 Technical Support', 'fa-broadcast-tower', 'active', 1),

('RTK GPS System', 'rtk-gps', 'Real-time kinematic GPS system for centimeter-level positioning accuracy. Perfect for detailed mapping and staking applications.', 'Real-time kinematic GPS system with centimeter-level accuracy.', 'equipment', 'Leica Geosystems', 'GS18 T', '3 Years', 'On-site Support Available', 'fa-satellite-dish', 'active', 2),

('Digital Level', 'digital-level', 'Advanced digital level for precise elevation measurements and construction layout work.', 'Advanced digital level for precise elevation measurements.', 'equipment', 'Topcon', 'DL-503', '2 Years', 'Email & Phone Support', 'fa-ruler-horizontal', 'active', 3),

('ArcGIS Software', 'arcgis', 'Comprehensive GIS software for spatial data analysis and mapping. Analyze patterns, visualize data, and create professional maps.', 'Comprehensive GIS software for spatial analysis and mapping.', 'software', 'Esri', 'ArcGIS Pro', 'Annual Subscription', 'Forum & Knowledge Base', 'fa-map', 'active', 4),

('Python for Data Analysis', 'python-analysis', 'Python packages for geospatial data processing and analysis. Custom solutions for data manipulation, visualization, and modeling.', 'Python packages for geospatial data processing and analysis.', 'software', 'Banner Fair', 'Custom Package', '6 Months Support', 'Email Support', 'fab fa-python', 'active', 5),

('AI for GIS Analysis', 'ai-gis', 'Training materials for implementing AI in geospatial data analysis. Learn machine learning and deep learning techniques for geospatial applications.', 'AI training materials for geospatial data analysis.', 'training', 'Banner Fair', 'Version 2025', '1 Year Updates', 'Forum Support', 'fa-brain', 'active', 6),

('Remote Sensing Analysis', 'remote-sensing', 'Comprehensive training on satellite imagery and remote sensing techniques. Master image classification, change detection, and environmental monitoring.', 'Comprehensive remote sensing training with satellite imagery techniques.', 'training', 'Banner Fair', 'Version 2025', '1 Year Updates', 'Email Support', 'fa-satellite', 'active', 7),

('Surveyor Starter Bundle', 'starter-bundle', 'Complete package for beginners in land surveying. Includes equipment, software, and training to get you started.', 'Complete starter package for land surveying beginners.', 'bundle', 'Banner Fair', 'Starter Bundle 2025', '2 Years', 'Priority Support', 'fa-layer-group', 'active', 8),

('GIS Professional Bundle', 'gis-bundle', 'Advanced GIS software and training for professionals. Comprehensive solution for spatial data analysis and mapping.', 'Advanced GIS software and training bundle for professionals.', 'bundle', 'Banner Fair', 'Pro Bundle 2025', '3 Years', 'Premium Support', 'fa-cubes', 'active', 9);

-- Insert default training programs
INSERT INTO training_programs (title, slug, description, short_description, category, price, duration, max_students, language, level, features, status, sort_order) VALUES
('Total Station Operation & Survey Data Processing', 'total-station-training', 'Learn to operate total stations and process survey data efficiently for various applications including construction layout, topographic surveys, and boundary determinations.', 'Comprehensive total station operation and data processing training.', 'surveying', 250000.00, '5 days', 12, 'English, French', 'beginner', '["Hands-on practice", "Field exercises", "Data processing", "Certificate", "Job placement assistance"]', 'active', 1),

('GIS Fundamentals with ArcGIS', 'gis-fundamentals', 'Master the fundamentals of Geographic Information Systems using ArcGIS software. Learn spatial analysis, data management, and map creation.', 'Master GIS fundamentals with ArcGIS software and spatial analysis.', 'gis', 300000.00, '1 week', 15, 'English', 'beginner', '["Software training", "Spatial analysis", "Map creation", "Certificate", "Project work"]', 'active', 2),

('Python for Data Analysis', 'python-data-analysis', 'Learn data manipulation, visualization, and modeling using Python programming language and its powerful libraries for geospatial applications.', 'Python programming for data analysis and geospatial applications.', 'programming', 200000.00, '3 days', 10, 'English', 'intermediate', '["Programming basics", "Data visualization", "Geospatial libraries", "Certificate", "Code examples"]', 'active', 3),

('Remote Sensing & Image Analysis', 'remote-sensing-training', 'Comprehensive training on satellite imagery analysis, image classification, change detection, and environmental monitoring techniques.', 'Satellite imagery analysis and remote sensing techniques training.', 'remote-sensing', 350000.00, '1 week', 12, 'English', 'intermediate', '["Satellite imagery", "Image classification", "Change detection", "Certificate", "Software tools"]', 'active', 4);

-- Insert sample research projects
INSERT INTO research_projects (title, slug, abstract, description, methodology, key_findings, authors, publication_date, category, status, featured) VALUES
('AI-Enhanced Land Cover Classification', 'ai-land-cover-classification', 'This research explores the application of artificial intelligence and machine learning techniques for automated land cover classification using satellite imagery.', 'Our research employs cutting-edge remote sensing techniques combined with machine learning algorithms to analyze multi-spectral satellite imagery for accurate land cover classification.', 'The research methodology includes data acquisition from multiple satellite sources, pre-processing and atmospheric correction, feature extraction and classification, and accuracy assessment and validation.', '["95% accuracy in land cover classification", "60% faster processing time", "Improved environmental monitoring", "Cost-effective solution"]', '["Dr. John Smith", "Dr. Jane Doe", "HATANGIMANA Fulgence"]', '2024-12-15', 'Remote Sensing', 'published', TRUE),

('Precision Agriculture Mapping', 'precision-agriculture-mapping', 'Development of precision agriculture techniques using drone-based mapping and sensor technology for optimized crop management.', 'This project focuses on developing advanced mapping techniques for precision agriculture using unmanned aerial vehicles and multi-spectral sensors.', 'Field data collection using drones, sensor calibration, data processing and analysis, and validation with ground truth measurements.', '["20% increase in crop yield", "30% reduction in fertilizer use", "Improved soil health monitoring", "Cost savings for farmers"]', '["Dr. Agricultural Expert", "HATANGIMANA Fulgence"]', '2024-11-20', 'Agriculture', 'completed', TRUE);
