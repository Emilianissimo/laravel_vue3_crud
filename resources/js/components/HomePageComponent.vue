<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.info(
                `%cHi there! This is the test project, included all necessary Web examples developed on actual and latest Laravel 9 + Vue.JS 3`, [
                    'padding: 10px',
                    'margin: 10px 0',
                    'font-size: 20px',
                    'border: 1px solid #ff2d20',
                    'border-radius: 10px',
                    'background:#ff2d208c',
                    'font-weight: 700'
                ].join(';')
                )
        }
    }
</script>
