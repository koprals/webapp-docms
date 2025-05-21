<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document Mangament System</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,800" rel="stylesheet" /> <!-- https://fonts.google.com/specimen/Open+Sans?selection.family=Open+Sans -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" /> <!-- https://fontawesome.com/ -->
    <link href="{{ asset('assets/slick/slick.css') }}" rel="stylesheet" /> <!-- https://kenwheeler.github.io/slick/ -->
    <link href="{{ asset('assets/slick/slick-theme.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" /> <!-- https://getbootstrap.com -->
	<link href="{{ asset('assets/css/templatemo-new-vision.css') }}" rel="stylesheet" />

</head>
<body>
    <!-- Page Header -->
    <div class="container-fluid">
        <div class="tm-site-header">
            <div class="row">
                <div class="col-12 tm-site-header-col">
                    <div class="tm-site-header-left" style="margin-top: 2%">
                        <i class="far fa-2x fa-file tm-site-icon"></i>
                        <h1 class="tm-site-name">Sistem Informasi</h1>
                    </div>
                    <div class="tm-site-header-right tm-menu-container-outer">
                        <!--Navbar-->
                        <nav class="navbar navbar-expand-lg">
                          <!-- Collapse button -->
                          <button class="navbar-toggler toggler-example" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1"
                            aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"><span class="dark-blue-text"><i
                                class="fas fa-bars fa-1x"></i></span></button>
                          <!-- Collapsible content -->
                          <div class="collapse navbar-collapse tm-nav" id="navbarSupportedContent1">
                            <!-- Links -->
                            <ul class="navbar-nav mr-auto">
                              <li class="nav-item">
                                <a class="nav-link tm-nav-link" href="{{ backpack_url('login') }}">Login</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link tm-nav-link" href="{{ route('register.klien') }}">Register</a>
                              </li>
                            </ul>
                            <!-- Links -->
                          </div>
                          <!-- Collapsible content -->
                        </nav>
                        <!--/.Navbar-->
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="tm-main-bg"></div>
            </div>
        </div>

        <!-- Main -->
        <main>
            <!-- Welcome section -->
            <section class="tm-welcome">
                <div class="row">
                    <div class="col-12">
                        <h2 class="tm-section-header tm-header-floating">Sistem Informasi Layanan</h2>
                    </div>
                </div>

                <div class="row tm-welcome-row">
                    @foreach ($jenis_permohonan as $data)
                        <article class="col-lg-6 tm-media">
                            <img src="{{ asset('assets/img/img-3x2-02.jpg') }}" alt="Welcome image" class="img-fluid tm-media-img" />
                            <div class="tm-media-body">
                                <a href="#exampleModal"
                                    class="tm-article-link"
                                    data-toggle="modal"
                                    data-target="#exampleModal"
                                    data-title="{{ $data->nama_jenis }}"
                                    data-desc="{{ $data->deskripsi }}">
                                    <h3 class="tm-article-title text-uppercase">{{ $data->nama_jenis }}</h3>
                                </a>
                                <div style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3;-webkit-box-orient: vertical;">
                                    {!! $data->deskripsi !!}
                                    {{-- {{ $data->deskripsi }} --}}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modalDescription">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer>
                Copyright &copy; 2025
            </footer>

        </main>
    </div>
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/templatemo-script.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.tm-article-link').on('click', function () {
                const title = $(this).data('title');
                const desc = $(this).data('desc');

                $('#exampleModalLabel').text(title);
                $('#modalDescription').html(desc);
            });
        });
    </script>
</body>
</html>
