SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `sciencescroll` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sciencescroll`;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    field VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    pdf_filename VARCHAR(255),
    archive tinyint(1) NOT NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE commentsarxiv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);


INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Mathematics Article 1', 'John Doe', '2023-01-15', 'Mathematics', 'This is a mathematics article about algebra.', 'math_article_1.pdf', 0),
('Mathematics Article 2', 'Jane Smith', '2023-02-20', 'Mathematics', 'A calculus paper discussing limits.', 'math_article_2.pdf', 0),
('Mathematics Article 3', 'Robert Johnson', '2023-03-10', 'Mathematics', 'Topology research in mathematics.', 'math_article_3.pdf', 0),
('Mathematics Article 4', 'Sarah Brown', '2023-04-05', 'Mathematics', 'Number theory and prime numbers.', 'math_article_4.pdf', 0),
('Mathematics Article 5', 'Michael Lee', '2023-05-12', 'Mathematics', 'Graph theory and its applications.', 'math_article_5.pdf', 0),
('Mathematics Article 6', 'Emily Wilson', '2023-06-22', 'Mathematics', 'Statistics in mathematical analysis.', 'math_article_6.pdf', 0),
('Mathematics Article 7', 'David Clark', '2023-07-18', 'Mathematics', 'Mathematical logic and proofs.', 'math_article_7.pdf', 0),
('Mathematics Article 8', 'Anna White', '2023-08-30', 'Mathematics', 'Abstract algebra and group theory.', 'math_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Physics Article 1', 'Mark Johnson', '2023-01-10', 'Physics', 'Quantum mechanics and wave-particle duality.', 'physics_article_1.pdf', 0),
('Physics Article 2', 'Linda Parker', '2023-02-18', 'Physics', 'Astrophysics and the study of galaxies.', 'physics_article_2.pdf', 0),
('Physics Article 3', 'Paul Anderson', '2023-03-25', 'Physics', 'The theory of relativity and time dilation.', 'physics_article_3.pdf', 0),
('Physics Article 4', 'Eva Wilson', '2023-04-09', 'Physics', 'Nuclear physics and particle interactions.', 'physics_article_4.pdf', 0),
('Physics Article 5', 'Kevin Davis', '2023-05-14', 'Physics', 'Thermodynamics and energy conservation.', 'physics_article_5.pdf', 0),
('Physics Article 6', 'Hannah Smith', '2023-06-28', 'Physics', 'Electromagnetism and Maxwells equations.', 'physics_article_6.pdf', 0),
('Physics Article 7', 'Thomas White', '2023-07-15', 'Physics', 'Fluid dynamics and fluid mechanics.', 'physics_article_7.pdf', 0),
('Physics Article 8', 'Olivia Clark', '2023-08-05', 'Physics', 'Optics and the behavior of light.', 'physics_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Quantitative Biology Article 1', 'Maria Rodriguez', '2023-01-05', 'Quantitative Biology', 'Genomic analysis and DNA sequencing techniques.', 'qb_article_1.pdf', 0),
('Quantitative Biology Article 2', 'Daniel Garcia', '2023-02-12', 'Quantitative Biology', 'Biostatistics and epidemiological modeling.', 'qb_article_2.pdf', 0),
('Quantitative Biology Article 3', 'Sophia Martinez', '2023-03-20', 'Quantitative Biology', 'Computational biology and protein structure prediction.', 'qb_article_3.pdf', 0),
('Quantitative Biology Article 4', 'Carlos Lopez', '2023-04-08', 'Quantitative Biology', 'Bioinformatics and genetic variation analysis.', 'qb_article_4.pdf', 0),
('Quantitative Biology Article 5', 'Isabella Wang', '2023-05-15', 'Quantitative Biology', 'Ecological modeling and population dynamics.', 'qb_article_5.pdf', 0),
('Quantitative Biology Article 6', 'William Chen', '2023-06-27', 'Quantitative Biology', 'Cancer research and tumor growth modeling.', 'qb_article_6.pdf', 0),
('Quantitative Biology Article 7', 'Olivia Smith', '2023-07-10', 'Quantitative Biology', 'Neuroscience and brain mapping techniques.', 'qb_article_7.pdf', 0),
('Quantitative Biology Article 8', 'Lucas Brown', '2023-08-12', 'Quantitative Biology', 'Systems biology and network analysis.', 'qb_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Computer Science Article 1', 'Emily Davis', '2023-01-03', 'Computer Science', 'Machine learning and deep neural networks.', 'cs_article_1.pdf', 0),
('Computer Science Article 2', 'Michael Wilson', '2023-02-10', 'Computer Science', 'Algorithms and data structures analysis.', 'cs_article_2.pdf', 0),
('Computer Science Article 3', 'Sophia Brown', '2023-03-18', 'Computer Science', 'Artificial intelligence and natural language processing.', 'cs_article_3.pdf', 0),
('Computer Science Article 4', 'Daniel Johnson', '2023-04-07', 'Computer Science', 'Computer vision and image recognition.', 'cs_article_4.pdf', 0),
('Computer Science Article 5', 'Linda Martinez', '2023-05-14', 'Computer Science', 'Distributed systems and cloud computing.', 'cs_article_5.pdf', 0),
('Computer Science Article 6', 'Carlos Smith', '2023-06-25', 'Computer Science', 'Cybersecurity and network security protocols.', 'cs_article_6.pdf', 0),
('Computer Science Article 7', 'Isabella Rodriguez', '2023-07-09', 'Computer Science', 'Software engineering and agile development.', 'cs_article_7.pdf', 0),
('Computer Science Article 8', 'William Garcia', '2023-08-11', 'Computer Science', 'Database management and query optimization.', 'cs_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Quantitative Finance Article 1', 'John Smith', '2023-01-07', 'Quantitative Finance', 'Portfolio optimization and risk management.', 'qf_article_1.pdf', 0),
('Quantitative Finance Article 2', 'Sarah Johnson', '2023-02-14', 'Quantitative Finance', 'Derivatives pricing and option strategies.', 'qf_article_2.pdf', 0),
('Quantitative Finance Article 3', 'David Wilson', '2023-03-22', 'Quantitative Finance', 'Time series analysis in financial markets.', 'qf_article_3.pdf', 0),
('Quantitative Finance Article 4', 'Olivia Martinez', '2023-04-06', 'Quantitative Finance', 'Risk-neutral pricing and arbitrage strategies.', 'qf_article_4.pdf', 0),
('Quantitative Finance Article 5', 'Lucas Garcia', '2023-05-13', 'Quantitative Finance', 'Financial modeling and Monte Carlo simulations.', 'qf_article_5.pdf', 0),
('Quantitative Finance Article 6', 'Eva Brown', '2023-06-24', 'Quantitative Finance', 'Hedging strategies and Black-Scholes model.', 'qf_article_6.pdf', 0),
('Quantitative Finance Article 7', 'Thomas Smith', '2023-07-08', 'Quantitative Finance', 'Credit risk assessment and credit derivatives.', 'qf_article_7.pdf', 0),
('Quantitative Finance Article 8', 'Mark Davis', '2023-08-10', 'Quantitative Finance', 'Financial engineering and structured products.', 'qf_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Statistics Article 1', 'Jane Wilson', '2023-01-04', 'Statistics', 'Descriptive statistics and data visualization.', 'stats_article_1.pdf', 0),
('Statistics Article 2', 'Robert Smith', '2023-02-11', 'Statistics', 'Hypothesis testing and confidence intervals.', 'stats_article_2.pdf', 0),
('Statistics Article 3', 'Hannah Johnson', '2023-03-19', 'Statistics', 'Regression analysis and ANOVA.', 'stats_article_3.pdf', 0),
('Statistics Article 4', 'Daniel Davis', '2023-04-05', 'Statistics', 'Bayesian statistics and Markov Chain Monte Carlo.', 'stats_article_4.pdf', 0),
('Statistics Article 5', 'Sophia Garcia', '2023-05-12', 'Statistics', 'Non-parametric statistics and rank tests.', 'stats_article_5.pdf', 0),
('Statistics Article 6', 'Carlos Brown', '2023-06-26', 'Statistics', 'Time series analysis and forecasting methods.', 'stats_article_6.pdf', 0),
('Statistics Article 7', 'Isabella Rodriguez', '2023-07-07', 'Statistics', 'Multivariate analysis and factor analysis.', 'stats_article_7.pdf', 0),
('Statistics Article 8', 'William Martinez', '2023-08-09', 'Statistics', 'Experimental design and sample size determination.', 'stats_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Electrical Engineering Article 1', 'Linda Smith', '2023-01-06', 'Electrical Engineering and Systems Science', 'Circuit design and analysis for electronics.', 'ee_article_1.pdf', 0),
('Electrical Engineering Article 2', 'Kevin Johnson', '2023-02-13', 'Electrical Engineering and Systems Science', 'Digital signal processing and image processing.', 'ee_article_2.pdf', 0),
('Electrical Engineering Article 3', 'Olivia Brown', '2023-03-21', 'Electrical Engineering and Systems Science', 'Power systems and electrical grid modeling.', 'ee_article_3.pdf', 0),
('Electrical Engineering Article 4', 'Lucas Wilson', '2023-04-04', 'Electrical Engineering and Systems Science', 'Control systems and robotics engineering.', 'ee_article_4.pdf', 0),
('Electrical Engineering Article 5', 'Emily Martinez', '2023-05-11', 'Electrical Engineering and Systems Science', 'Embedded systems and microcontroller programming.', 'ee_article_5.pdf', 0),
('Electrical Engineering Article 6', 'Carlos Davis', '2023-06-23', 'Electrical Engineering and Systems Science', 'Analog electronics and semiconductor devices.', 'ee_article_6.pdf', 0),
('Electrical Engineering Article 7', 'Maria Garcia', '2023-07-06', 'Electrical Engineering and Systems Science', 'Communication systems and wireless networks.', 'ee_article_7.pdf', 0),
('Electrical Engineering Article 8', 'Mark Smith', '2023-08-08', 'Electrical Engineering and Systems Science', 'Renewable energy systems and smart grids.', 'ee_article_8.pdf', 0);

INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES
('Economics Article 1', 'Hannah Smith', '2023-01-08', 'Economics', 'Macroeconomic analysis and fiscal policy.', 'economics_article_1.pdf', 0),
('Economics Article 2', 'Thomas Johnson', '2023-02-15', 'Economics', 'Microeconomics and market behavior.', 'economics_article_2.pdf', 0),
('Economics Article 3', 'Sophia Brown', '2023-03-23', 'Economics', 'International trade and exchange rate dynamics.', 'economics_article_3.pdf', 0),
('Economics Article 4', 'David Wilson', '2023-04-02', 'Economics', 'Labor economics and wage inequality.', 'economics_article_4.pdf', 0),
('Economics Article 5', 'Linda Davis', '2023-05-09', 'Economics', 'Environmental economics and sustainability.', 'economics_article_5.pdf', 0),
('Economics Article 6', 'Robert Martinez', '2023-06-20', 'Economics', 'Development economics and poverty alleviation.', 'economics_article_6.pdf', 0),
('Economics Article 7', 'Michael Smith', '2023-07-04', 'Economics', 'Behavioral economics and decision-making.', 'economics_article_7.pdf', 0),
('Economics Article 8', 'Eva Brown', '2023-08-06', 'Economics', 'Public finance and taxation policy.', 'economics_article_8.pdf', 0);






