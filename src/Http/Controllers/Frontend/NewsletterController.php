<?php

namespace Blog\Http\Controllers\Frontend;

use Blog\Http\Requests\Newsletters\Subscribe;
use Blog\Http\Requests\Newsletters\Unsubscribe;
use App\Http\Controllers\Controller;
use Blog\Models\Newsletter;
use Blog\Http\Requests\Newsletters\Store;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Blog\Notifications\SubscribedToNewsletter;


/**
 * Description of NewsletterController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class NewsletterController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model = new Newsletter;
        $model->fill($request->all());

        if ($model->save()) {

            session()->flash('message', 'Newsletter saved successfully');
            return redirect()->route('blog::newsletters.index');
        } else {
            session()->flash('message', 'Something is wrong while saving Newsletter');
        }
        return redirect()->back();
    }

    public function subscribe(Subscribe $request)
    {
        $newsletter = new Newsletter();
        $newsletter->fill($request->all());
        $newsletter->save();
        Notification::send(User::getAdmins(), new SubscribedToNewsletter($newsletter));
        return redirect()->back()->with('message', 'Thank you for subscribing to our Newsletter');
    }

    /**
     * @param Unsubscribe $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe(Unsubscribe $request)
    {
        $newsletter = Newsletter::where('email', $request->get('email'));
        if ($newsletter) {
            $newsletter->delete();
            return redirect()->route('blog::posts.home')->with('app_message', 'You have successfully unsubscribed from our Newsletter.');
        } else {
            return redirect()->route('blog::posts.home')->with('app_error', 'Sorry we do not find your email address');
        }
    }
}
