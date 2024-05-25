<html lang="en">
    <head>
        <title>Documentation</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.5.1/github-markdown.min.css" integrity="sha512-h/laqMqQKUXxFuu6aLAaSrXYwGYQ7qk4aYCQ+KJwHZMzAGaEoxMM6h8C+haeJTU1V6E9jrSUnjpEzX23OmV/Aw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mermaid/10.9.1/mermaid.min.js" integrity="sha512-6a80OTZVmEJhqYJUmYd5z8yHUCDlYnj6q9XwB/gKOEyNQV/Q8u+XeSG59a2ZKFEHGTYzgfOQKYEBtrZV7vBr+Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
            pre, code {
                color: #393a34;
                background-color: #ffffff;
            }

            .hl-keyword {
                color: #1e754f;
            }

            .hl-property {
                color: #59873a;
            }

            .hl-attribute {
                font-style: italic;
            }

            .hl-type {
                color: #EA4334;
            }

            .hl-generic {
                color: #1e754f;
            }

            .hl-value {
                color: #b56959;
            }

            .hl-variable {
                color: #b07d48;
            }

            .hl-comment {
                color: #a0ada0;
            }

            .hl-blur {
                filter: blur(2px);
            }

            .hl-strong {
                font-weight: bold;
            }

            .hl-em {
                font-style: italic;
            }

            .hl-addition {
                display: inline-block;
                min-width: 100%;
                background-color: #00FF0022;
            }

            .hl-deletion {
                display: inline-block;
                min-width: 100%;
                background-color: #FF000011;
            }

            .hl-gutter {
                display: inline-block;
                font-size: 0.9em;
                color: #555;
                padding: 0 1ch;
                user-select: none;
            }

            .hl-gutter-addition {
                background-color: #34A853;
                color: #fff;
            }

            .hl-gutter-deletion {
                background-color: #EA4334;
                color: #fff;
            }

        </style>
    </head>
    <body>
        <div class="flex">
            <div class="w-1/4 p-12">
                {{ $navigation }}
            </div>
            <div class="w-3/4 p-12 markdown-body">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
