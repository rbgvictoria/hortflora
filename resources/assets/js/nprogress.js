import NProgress from 'nprogress'
const nprogress = new NProgress();

nprogress.configure({
    template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><i class="fa fa-spinner fa-lg fa-spin"></i></div>'
});

export nprogress;
