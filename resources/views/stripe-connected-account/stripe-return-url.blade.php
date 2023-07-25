<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Peaceworc | Stripe Connected Account Created Successfully</title>
        <style>
            @-webkit-keyframes scaleAnimation {
                0% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5);
                }

                100% {
                    opacity: 1;
                    -webkit-transform: scale(1);
                    transform: scale(1);
                }
            }

            @keyframes scaleAnimation {
                0% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5);
                }

                100% {
                    opacity: 1;
                    -webkit-transform: scale(1);
                    transform: scale(1);
                }
            }

            @-webkit-keyframes drawCircle {
                0% {
                    stroke-dashoffset: 151px;
                }

                100% {
                    stroke-dashoffset: 0;
                }
            }

            @keyframes drawCircle {
                0% {
                    stroke-dashoffset: 151px;
                }

                100% {
                    stroke-dashoffset: 0;
                }
            }

            @-webkit-keyframes drawCheck {
                0% {
                    stroke-dashoffset: 36px;
                }

                100% {
                    stroke-dashoffset: 0;
                }
            }

            @keyframes drawCheck {
                0% {
                    stroke-dashoffset: 36px;
                }

                100% {
                    stroke-dashoffset: 0;
                }
            }

            @-webkit-keyframes fadeOut {
                0% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                }
            }

            @keyframes fadeOut {
                0% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                }
            }

            @-webkit-keyframes fadeIn {
                0% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }

            #successAnimationCircle {
                stroke-dasharray: 151px 151px;
                stroke: #1fae33;
            }

            #successAnimationCheck {
                stroke-dasharray: 36px 36px;
                stroke: #1fae33;
            }

            #successAnimationResult {
                fill: #1fae33;
                opacity: 0;
            }

            #successAnimation.animated {
                -webkit-animation: 1s ease-out 0s 1 both scaleAnimation;
                animation: 1s ease-out 0s 1 both scaleAnimation;
            }

            #successAnimation.animated #successAnimationCircle {
                -webkit-animation: 1s cubic-bezier(0.77, 0, 0.175, 1) 0s 1 both drawCircle, 0.3s linear 0.9s 1 both fadeOut;
                animation: 1s cubic-bezier(0.77, 0, 0.175, 1) 0s 1 both drawCircle, 0.3s linear 0.9s 1 both fadeOut;
            }

            #successAnimation.animated #successAnimationCheck {
                -webkit-animation: 1s cubic-bezier(0.77, 0, 0.175, 1) 0s 1 both drawCheck, 0.3s linear 0.9s 1 both fadeOut;
                animation: 1s cubic-bezier(0.77, 0, 0.175, 1) 0s 1 both drawCheck, 0.3s linear 0.9s 1 both fadeOut;
            }

            #successAnimation.animated #successAnimationResult {
                -webkit-animation: 0.3s linear 0.9s both fadeIn;
                animation: 0.3s linear 0.9s both fadeIn;
            }


            .thank_you_content {
                border-radius: 2px;
                background-color: rgb(255, 255, 255);
                max-width: 680px;
                width: 100%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                min-height: 400px;
                padding: 5em 3em;
            }

            .thank_you_content h3 {
                font-size: 30px;
            }

            .thank_you_content p {
                color: #252a3e;
            }
        </style>


    </head>
    <body>
        <div class="thank_you_mess">
            <div class="thank_you_content">
                <div class="success">
                    <svg id="successAnimation" class="animated" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                        viewBox="0 0 70 70">
                        <path id="successAnimationResult" fill="#D8D8D8"
                            d="M35,60 C21.1928813,60 10,48.8071187 10,35 C10,21.1928813 21.1928813,10 35,10 C48.8071187,10 60,21.1928813 60,35 C60,48.8071187 48.8071187,60 35,60 Z M23.6332378,33.2260427 L22.3667622,34.7739573 L34.1433655,44.40936 L47.776114,27.6305926 L46.223886,26.3694074 L33.8566345,41.59064 L23.6332378,33.2260427 Z" />
                        <circle id="successAnimationCircle" cx="35" cy="35" r="24" stroke="#979797"
                            stroke-width="2" stroke-linecap="round" fill="transparent" />
                        <polyline id="successAnimationCheck" stroke="#979797" stroke-width="2" points="23 34 34 43 47 27"
                            fill="transparent" />
                    </svg>
                </div>

                <h3>Hurray !!!</h3>
                <h4>Your Payout Account Has Been Created Successfully</h4>
                <small>You can close this page now.</small>
            </div>
        </div>
    </body>
</html>
