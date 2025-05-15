<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCAISO - TAQEEM | ISO Portal</title>
    <meta name="description" content="Tailwind CSS Saas HTML Template Is A Multi Purpose Landing Page Template, Corporate, Authentication, Launching Web, Agency or Business Startup, Clean, Modern, Creative, Multipurpose and Tailwind CSS Tailwind v3 etc."/>
    <meta name="author" content="Zoyothemes"/>
    <!-- Main Css -->
    <link href="{{asset("assets/style.css")}}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- =========== Main Section Start =========== -->
    <section class="relative h-screen w-full flex items-center justify-center bg-[conic-gradient(at_top_right,_var(--tw-gradient-stops))] from-[#ccf9df] to-[#d1d6ff]">
        <div class="relative max-w-lg md:mx-auto mx-6 w-full flex flex-col justify-center bg-white rounded-lg p-6">
            <div class="text-start mb-7">
                <a href="index.html" class="grow block mb-8">
                     <img class=" mx-auto" src="{{asset("assets/img/logo2.webp")}}" alt="images"  style="width: 450px">
                </a>

                <div class="text-center">
                  
                    <p class="text-base font-semibold text-light">In partnership with The Saudi Contractors Authority</p>
                </div>
            </div>
			@if($errors->any())
				<div class="alert" style="background: red !important;color:#fff !important;">
					{{ implode('', $errors->all(':message')) }}
				</div>
			@endif
            <form class="text-start w-full" action="{{route('login')}}" method="POST">
				@csrf
                    <div class="flex md:justify-between justify-center items-center mb-8 md:gap-9 gap-2">
                        <a href="javascript:;" class="w-full inline-flex items-center justify-center px-6 gap-4 py-2.5 font-medium backdrop-blur-2xl border border-gray-300 bg-primary text-white rounded-md transition-all duration-500">
                            <img src="{{asset("assets/img/google.png")}}" alt="" class="max-w-5 h-5 text-dark ">English
                        </a>
                        <a href="https://ar.myscaiso.com/" lang="ar" dir="rtl"   class="w-full inline-flex items-center justify-center px-6 gap-4 py-2.5 font-medium backdrop-blur-2xl border border-gray-300 bg-white text-dark rounded-md transition-all duration-500 group"><img src="{{asset("assets/img/facebook.png")}}" alt="" class="max-w-5 h-5 text-dark">العربية</a>
                    </div>   

                    <div class="mb-4">
                        
                        <input id="email-addon" class="block w-full rounded-md py-2.5 px-4 text-dark text-base font-medium border-gray-300 focus:gray-300 focus:border-primary focus:outline-0 focus:ring-0 placeholder:text-light placeholder:text-base" type="email" name="email" placeholder="Enter your email">
                    </div>

                    <div class="mb-4">
                       
                        <div class="flex">
                            <input type="password" id="password-addon" class="form-password text-dark text-base font-medium block w-full rounded-s-md py-2.5 px-4 border border-gray-300 focus:gray-300 focus:border-primary focus:outline-0 focus:ring-0 placeholder:text-light placeholder:text-base" placeholder="Enter your password" name="password">
                            <button type="button" data-hs-toggle-password='{"target": "#password-addon"}' class="inline-flex items-center justify-center py-2.5 px-4 border rounded-e-md -ms-px border-gray-300">
                                <i class="hs-password-active:hidden h-5 w-5 text-dark" data-lucide="eye"></i>
                                <i data-lucide="eye-off" class="hidden hs-password-active:block h-5 w-5 text-dark"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center flex-wrap gap-x-1 gap-y-2 mb-6 mt-3">
                        <div class="inline-flex items-center">
                            <input type="checkbox" id="checkbox-signin" class="h-4 w-4 text-base rounded border-gray-300 text-dark focus:ring focus:ring-default-950/30 focus:ring-offset-0">
                            <label class="text-base ms-2 text-light font-medium align-middle select-none" for="checkbox-signin">Remember me</label>
                        </div>
                        <a href="{{route('password.request')}}" class="text-base text-dark"><small>Forgot your password?</small></a>
                    </div>

                    <div class="text-center mb-7">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-primary font-bold text-base text-white rounded-md transition-all duration-500" type="submit">Log In</button>
                    </div>

                   
                </form>
        </div>
    </section>
    <!-- =========== Main Section End =========== -->
    
    <!-- Preline Js -->
    <script src="{{asset("js/en/js/preline.js")}}" ></script>

    <!-- Lucide Js -->
    <script src="{{asset("js/en/js/lucide.min.js")}}" ></script>

    <!-- Main App Js -->
    <script src="{{asset("js/en/js/app.js")}}" ></script>

    </body>
</html>