<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');


$menu_items = [
  [
    'href' => 'data/data.php', 
    'icon' => 'fas fa-database', 
    'text' => 'Veri İşlemleri',
    'description' => 'Ürün, stok ve veri yönetimi işlemleri'
  ],
  [
    'href' => 'custom/custom.php', 
    'icon' => 'fas fa-cogs', 
    'text' => 'Özelleştirme',
    'description' => 'Sistemi ihtiyaçlarınıza göre özelleştirin'
  ],
  [
    'href' => 'class/class.php', 
    'icon' => 'fas fa-chalkboard-teacher', 
    'text' => 'Sınıf Yönetimi',
    'description' => 'Sınıf ve kategorileri yönetin'
  ],
  [
    'href' => 'veritabani/veritabani.php', 
    'icon' => 'fas fa-server', 
    'text' => 'Veritabanı',
    'description' => 'Veritabanı yedekleme ve yönetim araçları'
  ],

];


?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modül Paneli - BSD Soft</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #224abe;
      --success-color: #1cc88a;
      --info-color: #36b9cc;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --light: #f8f9fc;
      --dark: #5a5c69;
      --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      --border-radius: 0.75rem;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body {
      background: linear-gradient(120deg, #f6f9fc 0%, #e9ecef 100%);
      min-height: 100vh;
      font-family: 'Inter', 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      line-height: 1.6;
    }
    
    .card {
      border: none;
      border-radius: var(--border-radius);
      transition: var(--transition);
      overflow: hidden;
      position: relative;
      background: white;
      box-shadow: var(--card-shadow);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(78, 115, 223, 0.02) 0%, rgba(34, 74, 190, 0.02) 100%);
      opacity: 0;
      transition: var(--transition);
      z-index: 1;
    }
    
    .card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: var(--card-shadow-hover);
    }
    
    .card:hover::before {
      opacity: 1;
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
      border: none;
      padding: 1.5rem 2rem;
      position: relative;
      z-index: 2;
    }
    
    .card-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
      z-index: -1;
    }
    
    .module-card {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(78, 115, 223, 0.1);
      border-left: 4px solid var(--primary-color);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }
    
    .module-card::after {
      content: '';
      position: absolute;
      top: 0;
      right: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(78, 115, 223, 0.05), transparent);
      transition: all 0.6s;
      z-index: 1;
    }
    
    .module-card:hover::after {
      right: 100%;
    }
    
    .module-card .card-body {
      padding: 2rem 1.5rem;
      position: relative;
      z-index: 2;
      pointer-events: none; /* Allow clicks to pass through to the anchor */
    }
    
    .module-card a {
      position: relative;
      z-index: 3;
      pointer-events: auto; /* Ensure the link is clickable */
    }
    
    .module-card i {
      font-size: 2.75rem;
      margin-bottom: 1.25rem;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      transition: var(--transition);
      display: inline-block;
      position: relative;
    }
    
    .module-card:hover i {
      transform: scale(1.15) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(78, 115, 223, 0.3));
    }
    
    .module-card .card-title {
      color: var(--dark);
      font-weight: 600;
      margin: 0;
      transition: var(--transition);
      font-size: 1.1rem;
      letter-spacing: -0.025em;
    }
    
    .module-card:hover .card-title {
      color: var(--primary-color);
      transform: translateX(5px);
    }
    
    .page-title {
      font-weight: 700;
      color: white;
      margin: 0;
      font-size: 1.75rem;
      letter-spacing: -0.025em;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .page-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.95rem;
      margin: 0.5rem 0 0;
      font-weight: 400;
    }
    
    .info-text {
      color: #6c757d;
      font-size: 0.95rem;
      line-height: 1.6;
    }
    
    .info-text i {
      color: var(--primary-color);
    }
    
    footer {
      color: #6c757d;
      font-size: 0.875rem;
    }
    
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Loading animation */
    .module-card {
      animation: slideInUp 0.6s ease-out;
      animation-fill-mode: both;
    }
    
    .module-card:nth-child(1) { animation-delay: 0.1s; }
    .module-card:nth-child(2) { animation-delay: 0.2s; }
    .module-card:nth-child(3) { animation-delay: 0.3s; }
    .module-card:nth-child(4) { animation-delay: 0.4s; }
    .module-card:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Responsive design improvements */
    @media (max-width: 768px) {
      .card-header {
        text-align: center;
        padding: 1.25rem 1.5rem;
      }
      
      .module-card .card-body {
        padding: 1.5rem 1.25rem;
      }
      
      .page-title {
        font-size: 1.5rem;
      }
      
      .module-card i {
        font-size: 2.25rem;
      }
    }
    
    /* Enhanced hover effects */
    .module-card {
      cursor: pointer;
    }
    
    .module-card:hover {
      border-left-width: 6px;
    }
    
    /* Glass morphism effect */
    .card-header {
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
  </style>
</head>
<body>

      
      <div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-grid text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Modül Yönetim Merkezi</h5>
                        <p class="text-muted small mb-0">Sistem modüllerine hızlı erişim ve yönetim</p>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Yazdır
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($menu_items as $item): ?>
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="<?= htmlspecialchars($item['href']) ?>" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
                            <i class="<?= htmlspecialchars($item['icon']) ?> fs-4 text-primary"></i>
                        </div>
                        <h6 class="card-title mb-2 fw-bold"><?= htmlspecialchars($item['text']) ?></h6>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
        </div>
    </div>














     

<?php include $_SERVER['DOCUMENT_ROOT'] . "/assets/src/include/footer.php"; ?>

<!-- Enhanced JavaScript for better user experience -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Add loading animation
    const cards = document.querySelectorAll('.module-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
    
    // Add click ripple effect
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(78, 115, 223, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
                z-index: 1000;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .module-card {
            position: relative;
            overflow: hidden;
        }
        
        /* Pulse animation for stats */
        .bg-primary, .bg-success, .bg-info, .bg-warning {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Add typing effect for title */
        .page-title {
            overflow: hidden;
            border-right: 2px solid rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            animation: typing 2s steps(12, end), blink-caret 0.75s step-end infinite;
        }
        
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: rgba(255, 255, 255, 0.7); }
        }
    `;
    document.head.appendChild(style);
    
    // Add hover sound effect (optional)
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            document.querySelectorAll('.module-card').forEach(card => {
                card.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                card.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }
    });
});
</script>

</body>
</html>
