        </div> <!-- End Content Wrapper -->
    </div> <!-- End Main Wrapper -->
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Active menu highlighting
        $(document).ready(function() {
            const currentPage = window.location.pathname.split('/').pop();
            
            $('.sidebar-menu a').each(function() {
                const href = $(this).attr('href');
                if (href === currentPage) {
                    $(this).addClass('active');
                    // Set page title based on active menu
                    const menuText = $(this).find('span').text();
                    if (menuText) {
                        $('#page-title').text(menuText);
                    }
                }
            });
            
            // Mobile menu toggle
            $('.mobile-menu-toggle').on('click', function() {
                $('.sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>
