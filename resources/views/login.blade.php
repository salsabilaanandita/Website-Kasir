<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name') }} - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-gradient-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="bg-gradient-primary-custom min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex justify-center items-center min-h-screen py-8">
            <div class="w-full max-w-5xl">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <div class="flex flex-wrap">
                        {{-- Image Section --}}
                        <div class="hidden lg:block lg:w-1/2">
                            <img src="{{ asset('template/img/login.jpg') }}" alt="Login Image" class="w-full h-full object-cover" style="min-height: 500px;">
                        </div>
                        
                        {{-- Form Section --}}
                        <div class="w-full lg:w-1/2 p-6 md:p-8">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <i class="fas fa-box-open text-white text-2xl"></i>
                                </div>
                                <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                                <p class="text-gray-500 text-sm">Please login to your account</p>
                            </div>
                            
                            @if(session('status'))
                                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-3"></i>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if ($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                                        <div>
                                            <strong class="font-semibold">Login Failed!</strong>
                                            <ul class="mt-1 text-sm list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf
                                
                                {{-- Email Field --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </div>
                                        <input type="email" 
                                               class="w-full pl-9 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror" 
                                               name="email" id="email" 
                                               placeholder="Enter your email" 
                                               value="{{ old('email') }}" required autofocus>
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                {{-- Password Field --}}
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-lock text-sm"></i>
                                        </div>
                                        <input type="password" 
                                               class="w-full pl-9 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('password') border-red-500 @enderror" 
                                               name="password" id="password" 
                                               placeholder="Enter your password" required>
                                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <hr class="my-6 border-gray-200">
                                
                                {{-- Submit Button --}}
                                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </button>
                            </form>
                            
                            {{-- Footer Text --}}
                            <div class="text-center mt-6">
                                <p class="text-xs text-gray-400">
                                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    
    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>