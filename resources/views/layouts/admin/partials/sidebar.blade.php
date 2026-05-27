<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __('text.toggle nav') }}">
                <span class="navbar-toggle-icon">
                    <span class="toggle-line"></span>
                </span>
            </button>
        </div>
        <a class="navbar-brand" href="{{ route('main.index') }}">
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ settingFilePath('app:logo') }}" alt="" width="75" />
                <span class="font-sans-serif">{{ config('app.name') }}</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                    <a class="nav-link @if($active === 'dashboard') active @endif" href="{{ route('admin.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                            <span class="nav-link-text ps-1">{{ __('title.dashboard') }}</span>
                        </div>
                    </a>
                </li>
                @if(auth()->user()->can('index', \App\Models\User::class) || auth()->user()->can('adminIndex', \App\Models\User::class))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">{{ __('title.users') }}</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider"/>
                            </div>
                        </div>
                        @can('index', \App\Models\User::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'users') active @endif" href="{{ route('admin.users.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-user-alt"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.users') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('adminIndex', \App\Models\User::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'admins') active @endif" href="{{ route('admin.admins.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-user-ninja"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.admins') }}</span>
                                </div>
                            </a>
                        @endcan
                    </li>
                @endif
                @if(auth()->user()->can('index', \App\Models\Health::class) || auth()->user()->can('index', \App\Models\Variable::class) || auth()->user()->can('index', \App\Models\Home::class) || auth()->user()->can('index', \App\Models\Option::class) || auth()->user()->can('indexHome', \App\Models\Category::class))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">{{ __('title.homes') }}</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider"/>
                            </div>
                        </div>
                        @can('index', \App\Models\Home::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'homes') active @endif" href="{{ route('admin.homes.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-home"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.homes') }}
                                        @php($count_homes = \App\Models\Home::query()->where('is_draft', false)->where('status', \App\Models\Home::PENDING)->count())
                                        @if($count_homes)
                                            <span class="badge rounded-pill p-2 badge-soft-warning">{{ number_format($count_homes) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Variable::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'homes-variables') active @endif" href="{{ route('admin.homes.variables.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-sliders-h"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.variables') }}
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Option::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'homes-options') active @endif" href="{{ route('admin.homes.options.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-cogs"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.options') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Health::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'homes-healths') active @endif" href="{{ route('admin.homes.healths.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-medkit"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.healths') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Safety::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'homes-safeties') active @endif" href="{{ route('admin.homes.safeties.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-user-shield"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.safeties') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('indexHome', \App\Models\Category::class)
                            <a class="nav-link @if($active === 'homes-categories') active @endif" href="{{ route('admin.homes.categories.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-list-alt"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.categories') }}</span>
                                </div>
                            </a>
                        @endcan
                    </li>
                @endif
                @if(auth()->user()->can('index', \App\Models\LandingPage::class) || auth()->user()->can('index', \App\Models\Article::class) || auth()->user()->can('indexArticle', \App\Models\Category::class))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">{{ __('title.blog') }}</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider"/>
                            </div>
                        </div>
                        @can('index', \App\Models\LandingPage::class)
                            <a class="nav-link @if($active === 'landing-pages') active @endif" href="{{ route('admin.landing-pages.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-map-marked-alt"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.landing_pages') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Article::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'articles') active @endif" href="{{ route('admin.articles.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-newspaper"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.articles') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('indexArticle', \App\Models\Category::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'article-categories') active @endif" href="{{ route('admin.articles.categories.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-list-alt"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.categories') }}</span>
                                </div>
                            </a>
                        @endcan
                    </li>
                @endif
                @if(auth()->user()->can('index', \App\Models\Newsletter::class) || auth()->user()->can('index', \App\Models\Withdraw::class) || auth()->user()->can('index', \App\Models\NewsletterSubscriber::class) || auth()->user()->can('index', \App\Models\Contact::class) || auth()->user()->can('index', \App\Models\Comment::class) || auth()->user()->can('index', \App\Models\Ticket::class) || auth()->user()->can('index', \App\Support\SmsTemplates::class))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">{{ __('title.general') }}</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider"/>
                            </div>
                        </div>
                        @can('index', \App\Models\Order::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'orders') active @endif" href="{{ route('admin.orders.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-shopping-basket"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.orders') }}
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Support\SmsTemplates::class)
                            <a class="nav-link @if($active === 'sms-templates') active @endif" href="{{ route('admin.sms-templates.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-sms"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.sms_templates') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Contact::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'contacts') active @endif" href="{{ route('admin.contacts.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-phone-alt"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.contact') }}
                                        @php($count_contacts = \App\Models\Contact::query()->where('is_seen', false)->count())
                                        @if($count_contacts)
                                            <span class="badge rounded-pill p-2 badge-soft-warning">{{ number_format($count_contacts) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Comment::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'comments') active @endif" href="{{ route('admin.comments.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-comments"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.comments') }}
                                        @php($count_comments = \App\Models\Comment::query()->pending()->count())
                                        @if($count_comments)
                                            <span class="badge rounded-pill p-2 badge-soft-warning">{{ number_format($count_comments) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Ticket::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'tickets') active @endif" href="{{ route('admin.tickets.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-ticket-alt"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.tickets') }}
                                        @php($count_tickets = \App\Models\Ticket::query()->where('status', \App\Models\Ticket::USER_ANSWERED)->count())
                                        @if($count_tickets)
                                            <span class="badge rounded-pill p-2 badge-soft-warning">{{ number_format($count_tickets) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Withdraw::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'withdraws') active @endif" href="{{ route('admin.withdraws.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-dollar-sign"></span></span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('title.withdraws') }}
                                        @php($count_withdraws = \App\Models\HostPayout::pendingBadgeCount())
                                        @if($count_withdraws)
                                            <span class="badge rounded-pill p-2 badge-soft-warning">{{ number_format($count_withdraws) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Discount::class)
                            <a class="nav-link @if($active === 'discounts') active @endif"
                               href="{{ route('admin.discounts.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-percent"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.discounts') }}</span>
                                </div>
                            </a>
                        @endcan
                        @if(auth()->user()->can('index', \App\Models\Newsletter::class) || auth()->user()->can('index', \App\Models\NewsletterSubscriber::class))
                            <a class="nav-link dropdown-indicator @if(in_array($active, ['newsletter', 'newsletter-subscribers'])) collapsed @endif" href="#newsletter-sidebar" role="button" data-bs-toggle="collapse" aria-controls="newsletter-sidebar">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-newspaper"></span></span>
                                    <span class="nav-link-text ps-1">@lang('title.newsletter')</span>
                                </div>
                            </a>
                            <ul class="nav false collapse @if(in_array($active, ['newsletter', 'newsletter-subscribers'])) show @endif" id="newsletter-sidebar" style="">
                                @can('index', \App\Models\Newsletter::class)
                                    <li class="nav-item">
                                        <a class="nav-link @if($active === 'newsletter') active @endif" href="{{ route('admin.newsletter.index') }}" aria-expanded="false">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">@lang('title.list')</span></div>
                                        </a>
                                    </li>
                                @endcan
                                @can('index', \App\Models\NewsletterSubscriber::class)
                                    <li class="nav-item">
                                        <a class="nav-link @if($active === 'newsletter-subscribers') active @endif" href="{{ route('admin.newsletter.subscribers.index') }}" aria-expanded="false">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">@lang('title.subscribers')</span>
                                            </div>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        @endif
                    </li>
                @endif
                @if(auth()->user()->can('index', \App\Models\Setting::class) || auth()->user()->can('index', \App\Models\Navbar::class) || auth()->user()->can('index', \Spatie\Permission\Models\Role::class) || auth()->user()->can('index', \App\Models\FAQ::class) || auth()->user()->can('indexFAQ', \App\Models\Category::class))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">{{ __('title.settings') }}</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider"/>
                            </div>
                        </div>
                        @can('index', \Spatie\Permission\Models\Role::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'roles') active @endif" href="{{ route('admin.roles.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-ruler"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.roles') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Navbar::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'navbar') active @endif" href="{{ route('admin.navbar.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-code-branch"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.navbar') }}</span>
                                </div>
                            </a>
                        @endcan
                        @if(auth()->user()->can('index', \App\Models\Consultant::class) || auth()->user()->can('index', \App\Models\FAQ::class) || auth()->user()->can('indexFAQ', \App\Models\Category::class))
                            <a class="nav-link dropdown-indicator @if(in_array($active, ['faq', 'faq-categories'])) collapsed @endif" href="#faq-sidebar" role="button" data-bs-toggle="collapse" aria-controls="faq-sidebar">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-question-circle"></span></span>
                                    <span class="nav-link-text ps-1">@lang('title.faq')</span>
                                </div>
                            </a>
                            <ul class="nav false collapse @if(in_array($active, ['faq', 'faq-categories'])) show @endif" id="faq-sidebar" style="">
                                @can('index', \App\Models\FAQ::class)
                                    <li class="nav-item">
                                        <a class="nav-link @if($active === 'faq') active @endif" href="{{ route('admin.faq.index') }}" aria-expanded="false">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">@lang('title.list')</span></div>
                                        </a>
                                    </li>
                                @endcan
                                @can('indexFAQ', \App\Models\Category::class)
                                    <li class="nav-item">
                                        <a class="nav-link @if($active === 'faq-categories') active @endif" href="{{ route('admin.faq.categories.index') }}" aria-expanded="false">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">@lang('title.categories')</span>
                                            </div>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        @endif
                        @can('index', \App\Models\Consultant::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'consultants') active @endif" href="{{ route('admin.consultants.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-user-check"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.consultants') }}</span>
                                </div>
                            </a>
                        @endcan
                        @can('index', \App\Models\Setting::class)
                            <!-- parent pages-->
                            <a class="nav-link @if($active === 'setting') active @endif" href="{{ route('admin.setting.index') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-cogs"></span></span>
                                    <span class="nav-link-text ps-1">{{ __('title.setting') }}</span>
                                </div>
                            </a>
                        @endcan
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
