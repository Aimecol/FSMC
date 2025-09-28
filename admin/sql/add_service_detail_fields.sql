-- Add detailed fields to services table for enhanced service pages
-- Execute this SQL to add Process, Benefits, Requirements, and FAQ fields

ALTER TABLE `services` 
ADD COLUMN `process_steps` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT 'JSON array of process steps' AFTER `gallery`,
ADD COLUMN `benefits` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT 'JSON array of service benefits' AFTER `process_steps`,
ADD COLUMN `requirements` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT 'JSON array of service requirements' AFTER `benefits`,
ADD COLUMN `faqs` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT 'JSON array of frequently asked questions' AFTER `requirements`;

-- Add JSON constraint checks (MySQL 5.7+)
-- ALTER TABLE `services` ADD CONSTRAINT `chk_process_steps_json` CHECK (JSON_VALID(`process_steps`));
-- ALTER TABLE `services` ADD CONSTRAINT `chk_benefits_json` CHECK (JSON_VALID(`benefits`));
-- ALTER TABLE `services` ADD CONSTRAINT `chk_requirements_json` CHECK (JSON_VALID(`requirements`));
-- ALTER TABLE `services` ADD CONSTRAINT `chk_faqs_json` CHECK (JSON_VALID(`faqs`));

-- Sample data structure for reference:
/*
process_steps: [
  {
    "step": 1,
    "title": "Initial Consultation",
    "description": "We meet with you to understand your needs and gather initial information.",
    "icon": "fas fa-comments"
  },
  {
    "step": 2,
    "title": "Site Survey",
    "description": "Our professional surveyors conduct a thorough assessment of your property.",
    "icon": "fas fa-map-marked-alt"
  }
]

benefits: [
  {
    "title": "Legal Protection",
    "description": "Official registration provides legal recognition of your ownership rights.",
    "icon": "fas fa-shield-alt"
  },
  {
    "title": "Property Value",
    "description": "Registered properties typically have higher market values.",
    "icon": "fas fa-money-bill-wave"
  }
]

requirements: [
  {
    "category": "Required Documents",
    "items": [
      "Identity documents (National ID, passport)",
      "Proof of ownership (purchase agreement, inheritance documents)",
      "Land acquisition documents",
      "Tax clearance certificate"
    ]
  },
  {
    "category": "Property Information",
    "items": [
      "Existing plot plans or sketches (if available)",
      "Property location details",
      "Boundary markers or reference points"
    ]
  }
]

faqs: [
  {
    "question": "How long does the registration process take?",
    "answer": "The duration varies depending on complexity, but typically takes 4-8 weeks from initial survey to certificate issuance."
  },
  {
    "question": "What is the cost of registration services?",
    "answer": "Costs vary based on property size and location. We provide detailed quotes after initial consultation with transparent pricing."
  }
]
*/
