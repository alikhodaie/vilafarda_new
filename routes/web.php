<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\Article\ArticleCategoryController;
use App\Http\Controllers\Admin\Article\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ConsultantController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\DiscountUseController;
use App\Http\Controllers\Admin\FAQ\FAQCategoryController;
use App\Http\Controllers\Admin\FAQ\FAQController;
use App\Http\Controllers\Admin\Home\HomeCategoryController;
use App\Http\Controllers\Admin\Home\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\Home\HomeCoverController;
use App\Http\Controllers\Admin\Home\HomeHealthController;
use App\Http\Controllers\Admin\Home\HomeOptionController;
use App\Http\Controllers\Admin\Home\HomeSafetyController;
use App\Http\Controllers\Admin\Home\HomeVariableController;
use App\Http\Controllers\Admin\Home\ImageController as AdminHomeImageController;
use App\Http\Controllers\Admin\Home\HomeDateController;
use App\Http\Controllers\Admin\LandingPageController as AdminLandingPageController;
use App\Http\Controllers\Admin\MainController as AdminMainController;
use App\Http\Controllers\Admin\NavbarController;
use App\Http\Controllers\Admin\Newsletter\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\Newsletter\NewsletterSubscriberController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SmsTemplateController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\Ticket\MessageController as AdminTicketMessageController;
use App\Http\Controllers\Admin\Ticket\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\TinyMceEditorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginTempController;
use App\Http\Controllers\Auth\Password\ForgetPasswordController;
use App\Http\Controllers\Auth\Password\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\CommentController as DashboardCommentsController;
use App\Http\Controllers\Dashboard\FavoriteController;
use App\Http\Controllers\Dashboard\Home\HomeController as DashboardHomeController;
use App\Http\Controllers\Dashboard\Home\HomeCustomController;
use App\Http\Controllers\Dashboard\Home\HomeImageController;
use App\Http\Controllers\Dashboard\Home\HomeValidateController;
use App\Http\Controllers\Dashboard\MainController as DashboardMainController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\Profile\AvatarController;
use App\Http\Controllers\Dashboard\Profile\ProfileController;
use App\Http\Controllers\Dashboard\RentController as DashboardRentController;
use App\Http\Controllers\Dashboard\RentReviewController;
use App\Http\Controllers\Dashboard\TicketController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Main\Article\ArticleCommentController;
use App\Http\Controllers\Main\Article\ArticleController;
use App\Http\Controllers\Main\ContactController;
use App\Http\Controllers\Main\Home\HomeCommentController;
use App\Http\Controllers\Main\Home\HomeController;
use App\Http\Controllers\Main\Home\HomeFavoriteController;
use App\Http\Controllers\Main\LandingPageController;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Main\NewsletterController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Webhook\SmsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap/static.xml', [SitemapController::class, 'staticPages'])->name('sitemap.static');
Route::get('/sitemap/landings.xml', [SitemapController::class, 'landings'])->name('sitemap.landings');
Route::get('/sitemap/homes.xml', [SitemapController::class, 'homes'])->name('sitemap.homes');
Route::get('/sitemap/articles.xml', [SitemapController::class, 'articles'])->name('sitemap.articles');

# region Main
Route::name('main.')->group(function (){
    Route::get('/', [MainController::class, 'index'])->middleware('detect.mobile')->name('index');
    Route::get('/provinces', [MainController::class, 'provinces'])->name('provinces');

    Route::get('/submit/home', [MainController::class, 'submitHome'])->middleware('detect.mobile')->name('submit.home');

    Route::match(['get', 'post'], '/call-back', [MainController::class, 'callBack'])->name('call-back');

    Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

    # region Auth
    Route::middleware('guest')->group(function (){
        Route::get('/login', [LoginController::class, 'form'])->middleware('detect.mobile')->name('login.form');
        Route::post('/login', [LoginController::class, 'login'])->name('login');

        Route::get('/login-temp-send', [LoginTempController::class, 'form'])->middleware('detect.mobile')->name('login.temp.send.form');
        Route::post('/login-temp-send', [LoginTempController::class, 'send'])->name('login.temp.send');
        Route::get('/login-temp', [LoginTempController::class, 'loginForm'])->middleware('detect.mobile')->name('login.temp.form');
        Route::post('/login-temp', [LoginTempController::class, 'login'])->name('login.temp');

        Route::get('/register', [RegisterController::class, 'form'])->middleware('detect.mobile')->name('register.form');
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
    # endregion

    # region Contact
    Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');
    # endregion

    # region Pages
    Route::get('/privacy', [MainController::class, 'privacy'])->middleware('detect.mobile')->name('privacy');
    Route::get('/contact-us', [MainController::class, 'contactUs'])->middleware('detect.mobile')->name('contact-us');
    Route::get('/faq', [MainController::class, 'faq'])->middleware('detect.mobile')->name('faq');
    Route::get('/about-us', [MainController::class, 'aboutUs'])->middleware('detect.mobile')->name('about-us');
    Route::get('/add-to-home/ios', [MainController::class, 'addToHomeIos'])->middleware('detect.mobile')->name('add-to-home.ios');
    Route::get('/add-to-home/android', [MainController::class, 'addToHomeAndroid'])->middleware('detect.mobile')->name('add-to-home.android');
    # endregion

    # region Articles
    Route::prefix('/articles')->name('articles.')->group(function (){
        Route::get('/', [ArticleController::class, 'index'])->middleware('detect.mobile')->name('index');
        Route::get('/{id}/{slug}', [ArticleController::class, 'show'])->middleware('detect.mobile')->name('show');

        Route::post('/{article}', [ArticleCommentController::class, 'store'])->name('comments.store');
    });
    # endregion

    # region Landing pages (SEO)
    Route::get('/l/{landing_page}', [LandingPageController::class, 'show'])
        ->middleware('detect.mobile')
        ->name('landing-pages.show');
    # endregion

    # region Homes
    Route::prefix('/homes')->name('homes.')->middleware('detect.mobile')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::get('/nearby', [HomeController::class, 'nearby'])->name('nearby');
        Route::get('/map-data', [HomeController::class, 'mapData'])->name('map-data');

        $registerHomeRoutes = function (string $prefix, bool $legacy) {
            $path = ($prefix !== '' ? $prefix.'/' : '').'{home}';
            $suffix = $legacy ? '.legacy' : '';
            $where = $legacy ? ['home' => '[0-9]+'] : [];

            Route::get("{$path}/visit", [HomeController::class, 'trackClick'])
                ->name('visit'.$suffix)
                ->where($where);
            Route::get($path, [HomeController::class, 'show'])
                ->name('show'.$suffix)
                ->where($where);
            Route::post("{$path}/reserve", [HomeController::class, 'reserve'])
                ->middleware('auth')
                ->name('reserve'.$suffix)
                ->where($where);
            Route::post($path, [HomeCommentController::class, 'store'])
                ->middleware('auth')
                ->name('comments.store'.$suffix)
                ->where($where);
            Route::prefix("{$path}/favorite")
                ->name('favorites'.$suffix.'.')
                ->middleware('auth')
                ->where($where)
                ->group(function () {
                    Route::post('/', [HomeFavoriteController::class, 'store'])->name('store');
                    Route::delete('/', [HomeFavoriteController::class, 'destroy'])->name('destroy');
                });
        };

        // آدرس سئو: /homes/r/{slug}-{id}
        $registerHomeRoutes('r', false);
        // سازگاری با لینک‌های قدیمی شبکه‌های اجتماعی: /homes/{id}
        $registerHomeRoutes('', true);
    });
    # endregion
});
# endregion

# region Dashboard
Route::prefix('/dashboard')->name('dashboard.')->middleware(['auth', 'detect.mobile'])->group(function (){
    Route::get('/', [DashboardMainController::class, 'index'])->name('index');
    Route::get('/provinces', [DashboardMainController::class, 'provinces'])->name('provinces');

    # region Profile
    Route::prefix('/profile')->name('profile.')->group(function (){
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        Route::prefix('/avatar')->name('avatar.')->group(function (){
            Route::patch('/', [AvatarController::class, 'update'])->name('update');
            Route::delete('/', [AvatarController::class, 'destroy'])->name('destroy');
        });
    });
    # endregion

    # region Favorites
    Route::prefix('/favorites')->name('favorites.')->group(function (){
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::delete('/{favorite}', [FavoriteController::class, 'destroy'])->name('destroy');
    });
    # endregion

    #  region Tickets
    Route::resource('tickets', TicketController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/tickets/{ticket}', [TicketController::class, 'reply'])->name('tickets.reply');
    #  endregion

    # region Homes
    Route::resource('homes', DashboardHomeController::class)->except(['store']);
    Route::patch('homes/{home}/host-status', [DashboardHomeController::class, 'updateHostStatus'])->name('homes.host-status');
    Route::post('homes/{home}/draft', [DashboardHomeController::class, 'saveDraft'])->name('homes.draft');
    Route::post('homes/{home}', [DashboardHomeController::class, 'store'])->name('homes.store');

    Route::prefix('/homes')->name('homes.')->group(function (){
        Route::prefix('/validate/{home}')->name('validate.')->group(function (){
            Route::post('/store/step1', [HomeValidateController::class, 'storeStep1'])->name('store.step1');
            Route::post('/store/step2', [HomeValidateController::class, 'storeStep2'])->name('store.step2');
            Route::post('/store/step3', [HomeValidateController::class, 'storeStep3'])->name('store.step3');
            Route::post('/store/step4', [HomeValidateController::class, 'storeStep4'])->name('store.step4');
            Route::post('/store/step5', [HomeValidateController::class, 'storeStep5'])->name('store.step5');
            Route::post('/store/step6', [HomeValidateController::class, 'storeStep6'])->name('store.step6');
            Route::post('/store/step7', [HomeValidateController::class, 'storeStep7'])->name('store.step7');
            Route::post('/store/step8', [HomeValidateController::class, 'storeStep8'])->name('store.step8');
            Route::post('/store/step9', [HomeValidateController::class, 'storeStep9'])->name('store.step9');
            Route::post('/store/step10', [HomeValidateController::class, 'storeStep10'])->name('store.step10');
            Route::post('/store/step11', [HomeValidateController::class, 'storeStep11'])->name('store.step11');
            Route::post('/store/step12', [HomeValidateController::class, 'storeStep12'])->name('store.step12');
            Route::post('/store/step13', [HomeValidateController::class, 'storeStep13'])->name('store.step13');
            Route::post('/store/step14', [HomeValidateController::class, 'storeStep14'])->name('store.step14');
        });

        # region Images
        Route::prefix('/{home}/image')->name('image.')->group(function (){
            Route::get('/', [HomeImageController::class, 'index'])->name('index');
            Route::post('/', [HomeImageController::class, 'store'])->name('store');
            Route::patch('/{image}', [HomeImageController::class, 'update'])->name('update');
            Route::delete('/{image}', [HomeImageController::class, 'destroy'])->name('delete');
        });
        # endregion

        # region Custom
        Route::prefix('/{home}/custom')->name('custom.')->group(function (){

            # region Date
            Route::prefix('/date')->name('date.')->group(function (){
                Route::get('/', [HomeCustomController::class, 'showDate'])->name('show');
                Route::post('/', [HomeCustomController::class, 'storeDate'])->name('store');
//                Route::delete('/', [HomeCustomController::class, 'destroyDate'])->name('destroy');
                Route::post('/fast-reserve', [HomeCustomController::class, 'updateFastReserve'])->name('update.fast.reserve');
            });
            # endregion

            # region Price
            Route::prefix('/price')->name('price.')->group(function (){
                Route::get('/', [HomeCustomController::class, 'showPrice'])->name('show');
                Route::put('/', [HomeCustomController::class, 'updatePrice'])->name('update');
            });
            # endregion

            # region Address
            Route::prefix('/address')->name('address.')->group(function (){
                Route::get('/', [HomeCustomController::class, 'showAddress'])->name('show');
                Route::put('/', [HomeCustomController::class, 'updateAddress'])->name('update');
            });
            # endregion

            # region Option
            Route::prefix('/option')->name('option.')->group(function (){
                Route::get('/', [HomeCustomController::class, 'showOption'])->name('show');
                Route::put('/', [HomeCustomController::class, 'updateOption'])->name('update');
            });
            # endregion

            # region Images
            Route::prefix('/images')->name('images.')->group(function (){
                Route::get('/', [HomeCustomController::class, 'showImage'])->name('show');
                Route::put('/', [HomeCustomController::class, 'updateImage'])->name('update');
                Route::delete('/', [HomeCustomController::class, 'deleteImage'])->name('delete');
            });
            # endregion
        });
        # endregion
    });
    # endregion

    # region Rents
    Route::prefix('/rents')->name('rents.')->group(function (){
        Route::get('/', [DashboardRentController::class, 'index'])->middleware('detect.mobile')->name('index');
        Route::get('/{order}/review', [RentReviewController::class, 'create'])->name('review.create');
        Route::post('/{order}/review', [RentReviewController::class, 'store'])->name('review.store');
        Route::get('/{order}', [DashboardRentController::class, 'show'])->middleware('detect.mobile')->name('show');
        Route::get('/{order}/contract', [DashboardRentController::class, 'contract'])->name('contract');
        Route::patch('/{order}/cancel', [DashboardRentController::class, 'cancel'])->name('cancel');
        Route::post('/{order}/discount', [DashboardRentController::class, 'discount'])->name('discount');
        Route::post('/{order}/pay', [DashboardRentController::class, 'pay'])->name('pay');
    });
    # endregion

    # region Orders
    Route::prefix('/orders')->name('orders.')->group(function (){
        Route::get('/', [DashboardOrderController::class, 'index'])->middleware('detect.mobile')->name('index');
        Route::get('/{order}', [DashboardOrderController::class, 'show'])
            ->middleware('detect.mobile')
            ->where('order', '[0-9]+')
            ->name('show');
        Route::get('/{order}/contract', [DashboardOrderController::class, 'contract'])
            ->where('order', '[0-9]+')
            ->name('contract');
        Route::patch('/{order}/accept', [DashboardOrderController::class, 'accept'])
            ->where('order', '[0-9]+')
            ->name('accept');
        Route::patch('/{order}/reject', [DashboardOrderController::class, 'reject'])
            ->where('order', '[0-9]+')
            ->name('reject');
    });
    # endregion

    # region Financial
    Route::get('/host-transactions', [TransactionController::class, 'hostIndex'])->name('host-transactions.index');
    Route::get('/guest-transactions', [TransactionController::class, 'guestIndex'])->name('guest-transactions.index');
    Route::redirect('/transactions', '/dashboard/host-transactions')->name('transactions.index');
    # endregion

    # region Comments
    Route::resource('comments', DashboardCommentsController::class)->only(['index', 'store']);
    # endregion
});
# endregion

# region Admin
Route::prefix('/admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (){
    Route::get('/', [AdminMainController::class, 'index'])->name('index');
    Route::get('/order-count', [AdminMainController::class, 'orderCount'])->name('order-count');

    Route::post('/tinymce/upload', [TinyMceEditorController::class, 'upload'])->name('tinymce_upload');

    # region Users
    Route::resource('users', UserController::class)->except(['show']);
    # endregion

    # region Admin
    Route::resource('admins', AdminController::class)->except(['show']);
    # endregion

    # region Roles
    Route::resource('roles', RoleController::class)->except(['show']);
    # endregion

    # region Navbar
    Route::resource('navbar', NavbarController::class)->except(['show']);
    # endregion

    # region Comments
    Route::resource('comments', CommentController::class)->except(['show']);
    # endregion

    # region Articles
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::prefix('articles')->name('articles.')->group(function (){
        # region Categories
        Route::resource('categories', ArticleCategoryController::class)->except(['show']);
        # endregion
    });
    # endregion

    # region Homes
    Route::prefix('homes')->name('homes.')->group(function () {
        # region Covers
        Route::prefix('/{home}/cover')->name('covers.')->group(function (){
            Route::put('/', [HomeCoverController::class, 'update'])->name('update');
            Route::delete('/', [HomeCoverController::class, 'delete'])->name('delete');
        });
        # endregion

        # region Images
        Route::prefix('/{home}/image')->name('image.')->group(function (){
            Route::get('/', [AdminHomeImageController::class, 'index'])->name('index');
            Route::post('/bulk-delete', [AdminHomeImageController::class, 'bulkDestroy'])->name('bulk-delete');
            Route::post('/', [AdminHomeImageController::class, 'store'])->name('store');
            Route::patch('/{image}', [AdminHomeImageController::class, 'update'])->name('update');
            Route::delete('/{image}', [AdminHomeImageController::class, 'destroy'])->name('delete');
        });
        # endregion

        # region Date
        Route::prefix('/{home}/date')->name('date.')->group(function (){
            Route::get('/', [HomeDateController::class, 'show'])->name('show');
            Route::post('/', [HomeDateController::class, 'store'])->name('store');
            Route::delete('/', [HomeDateController::class, 'destroy'])->name('destroy');
            Route::post('/fast-reserve', [HomeDateController::class, 'updateFastReserve'])->name('update.fast.reserve');
        });
        # endregion

        # region Options
        Route::resource('options', HomeOptionController::class)->except(['show']);
        # endregion

        # region Healths
        Route::resource('healths', HomeHealthController::class)->except(['show']);
        # endregion

        # region Safeties
        Route::resource('safeties', HomeSafetyController::class)->parameter('safeties', 'safety')->except(['show']);
        # endregion

        # region Variables
        Route::resource('variables', HomeVariableController::class)->except(['show']);
        # endregion

        # region Categories
        Route::resource('categories', HomeCategoryController::class)->except(['show']);
        # endregion

        Route::resource('', AdminHomeController::class)->parameter('', 'home');
    });
    # endregion

    # region Orders
    Route::prefix('orders')->name('orders.')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update.status');
        Route::get('/{order}/contract', [OrderController::class, 'contract'])->name('contract');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });
    # endregion

    # region Tags
    Route::resource('tags', TagController::class)->except(['show', 'create', 'edit', 'update', 'destroy']);
    # endregion

    # region Tickets
    Route::resource('tickets', AdminTicketController::class)->except(['edit']);
    Route::prefix('/tickets/{ticket}')->name('tickets.')->group(function (){
        Route::resource('messages' ,AdminTicketMessageController::class)->except(['show', 'create', 'index']);
    });
    # endregion

    # region Contact
    Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);
    # endregion

    # region Ajax
    Route::prefix('/ajax')->name('ajax.')->group(function (){
        Route::get('users', [AjaxController::class, 'users'])->name('users');
        Route::get('homes', [AjaxController::class, 'homes'])->name('homes');
        Route::get('articles', [AjaxController::class, 'articles'])->name('articles');
    });
    # endregion

    # region Landing pages
    Route::resource('landing-pages', AdminLandingPageController::class)->except(['show']);
    # endregion

    # region FAQ
    Route::resource('faq', FAQController::class)->except(['show']);
    Route::prefix('faq')->name('faq.')->group(function (){
        # region Categories
        Route::resource('categories', FAQCategoryController::class)->except(['show']);
        # endregion
    });
    # endregion

    # region Page
    Route::prefix('/setting')->name('setting.')->group(function (){
        Route::get('/', [SettingController::class, 'index'])->name('index');

        Route::put('/general', [SettingController::class, 'general'])->name('general');
        Route::put('/payment', [SettingController::class, 'payment'])->name('payment');
        Route::put('/commission', [SettingController::class, 'commission'])->name('commission');
        Route::put('/reject-policy', [SettingController::class, 'rejectPolicy'])->name('reject-policy');
        Route::put('/index-page', [SettingController::class, 'indexPage'])->name('index-page');
        Route::put('/contact-us', [SettingController::class, 'contactUs'])->name('contact-us');
        Route::put('/privacy', [SettingController::class, 'privacy'])->name('privacy');
        Route::put('/about-us', [SettingController::class, 'aboutUs'])->name('about-us');
        Route::put('/faq', [SettingController::class, 'faq'])->name('faq');
        Route::put('/footer', [SettingController::class, 'footer'])->name('footer');
        Route::put('/seo', [SettingController::class, 'seo'])->name('seo');
        Route::put('/submit-home', [SettingController::class, 'submitHome'])->name('submit-home');
    });
    # endregion

    # region Newsletter
    Route::prefix('/newsletter')->name('newsletter.')->group(function (){
        # region Newsletter Subscribers
        Route::resource('subscribers', NewsletterSubscriberController::class)->except(['show', 'create', 'store', 'edit', 'update']);
        # endregion
    });
    Route::resource('newsletter', AdminNewsletterController::class)->except(['edit', 'update']);
    # endregion

    # region Consultant
    Route::resource('consultants', ConsultantController::class);
    # endregion

    # region Withdraw
    Route::post('withdraws/bulk-paid', [WithdrawController::class, 'bulkMarkPaid'])->name('withdraws.bulk-paid');
    Route::resource('withdraws', WithdrawController::class)->except(['destroy']);
    # endregion

    # region SMS Templates
    Route::get('/sms-templates', [SmsTemplateController::class, 'index'])->name('sms-templates.index');
    # endregion

    # region Discounts
    Route::get('/discounts/{discount}/edit/use', [DiscountUseController::class, 'edit'])->name('discounts.edit.use');
    Route::post('/discounts/{discount}/edit/use', [DiscountUseController::class, 'update'])->name('discounts.update.use');
    Route::resource('discounts', DiscountController::class);
    # endregion
});
# endregion

// region Webhook
Route::prefix('webhook')->group(function (){
    Route::any('/sms', SmsController::class);
});
// endregion
