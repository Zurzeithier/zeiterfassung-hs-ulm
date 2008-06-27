package timetracking;

import beans.UserBean;
import com.sun.rave.web.ui.appbase.AbstractPageBean;
import exceptions.DBException;
import handlers.BookingHandler;
import handlers.UserHandler;
import javax.faces.FacesException;

/**
 * <p>Page bean that corresponds to a similarly named JSP page.  This
 * class contains component definitions (and initialization code) for
 * all components that you have defined on this page, as well as
 * lifecycle methods and event handlers where you may add behavior
 * to respond to incoming events.</p>
 *
 * @author manuel, steffen
 */
public class Login extends AbstractPageBean
{
    // <editor-fold defaultstate="collapsed" desc="Managed Component Definition">
    /**
     * <p>Automatically managed component initialization.  <strong>WARNING:</strong>
     * This method is automatically generated, so any user-specified code inserted
     * here is subject to being replaced.</p>
     */
    private void _init() throws Exception
    {
    }

    // </editor-fold>
    private String username = null;
    private String password = null;
    private String status = null;
    private boolean message = false;

    /**
     * 
     * @return password
     */
    public String getPassword()
    {
        return password;
    }

    /**
     * 
     * @param password
     */
    public void setPassword(String password)
    {
        this.password = password;
    }

    /**
     * 
     * @return username
     */
    public String getUsername()
    {
        return username;
    }

    /**
     * 
     * @param username
     */
    public void setUsername(String username)
    {
        this.username = username;
    }

    /**
     * 
     * @return status
     */
    public String getStatus()
    {
        return status;
    }

    /**
     * 
     * @param status
     */
    public void setStatus(String status)
    {
        this.status = status;
    }

    /**
     * 
     * @return message
     */
    public boolean isMessage()
    {
        return message;
    }

    /**
     * 
     * @param message
     */
    public void setMessage(boolean message)
    {
        this.message = message;
    }

    /**
     * <p>Construct a new Page bean instance.</p>
     */
    public Login()
    {
    }

    /**
     * <p>Callback method that is called whenever a page is navigated to,
     * either directly via a URL, or indirectly via page navigation.
     * Customize this method to acquire resources that will be needed
     * for event handlers and lifecycle methods, whether or not this
     * page is performing post back processing.</p>
     * 
     * <p>Note that, if the current request is a postback, the property
     * values of the components do <strong>not</strong> represent any
     * values submitted with this request.  Instead, they represent the
     * property values that were saved for this view when it was rendered.</p>
     */
    @Override
    public void init()
    {
        // Perform initializations inherited from our superclass
        super.init();
        // Perform application initialization that must complete
        // *before* managed components are initialized
        // TODO - add your own initialiation code here

        // <editor-fold defaultstate="collapsed" desc="Managed Component Initialization">
        // Initialize automatically managed components
        // *Note* - this logic should NOT be modified
        try
        {
            _init();
        }
        catch (Exception e)
        {
            log("Page1 Initialization Failure", e);
            throw e instanceof FacesException ? (FacesException) e : new FacesException(e);
        }

    // </editor-fold>
    // Perform application initialization that must complete
    // *after* managed components are initialized
    // TODO - add your own initialization code here
    }

    /**
     * <p>Callback method that is called after the component tree has been
     * restored, but before any event processing takes place.  This method
     * will <strong>only</strong> be called on a postback request that
     * is processing a form submit.  Customize this method to allocate
     * resources that will be required in your event handlers.</p>
     */
    @Override
    public void preprocess()
    {
    }

    /**
     * <p>Callback method that is called just before rendering takes place.
     * This method will <strong>only</strong> be called for the page that
     * will actually be rendered (and not, for example, on a page that
     * handled a postback and then navigated to a different page).  Customize
     * this method to allocate resources that will be required for rendering
     * this page.</p>
     */
    @Override
    public void prerender()
    {
    }

    /**
     * <p>Callback method that is called after rendering is completed for
     * this request, if <code>init()</code> was called (regardless of whether
     * or not this was the page that was actually rendered).  Customize this
     * method to release resources acquired in the <code>init()</code>,
     * <code>preprocess()</code>, or <code>prerender()</code> methods (or
     * acquired during execution of an event handler).</p>
     */
    @Override
    public void destroy()
    {
    }

    /**
     * <p>Return a reference to the scoped data bean.</p>
     *
     * @return reference to the scoped data bean
     */
    protected SessionBean1 getSessionBean1()
    {
        return (SessionBean1) getBean("SessionBean1");
    }

    /**
     * <p>Return a reference to the scoped data bean.</p>
     *
     * @return reference to the scoped data bean
     */
    protected RequestBean1 getRequestBean1()
    {
        return (RequestBean1) getBean("RequestBean1");
    }

    /**
     * <p>Return a reference to the scoped data bean.</p>
     *
     * @return reference to the scoped data bean
     */
    protected ApplicationBean1 getApplicationBean1()
    {
        return (ApplicationBean1) getBean("ApplicationBean1");
    }

    /**
     * Prefaces the login routine
     * @return "loginSucessfull"
     * @throws exceptions.DBException
     */
    public String loginButton_action() throws DBException
    {
        UserBean user = UserHandler.loginUser(username, password);
        if (user != null)
        {
            getSessionBean1().setUser(user);
            return "loginSucessfull";
        }
        else
        {
            status = "Benutzername/ Passwort ist falsch!";
        }

        return null;
    }

    /**
     * Prefaces the COME booking routine
     * @return "loginSucessfull"
     * @throws exceptions.DBException
     */
    public String comePushButton_action() throws DBException
    {
        UserBean user = UserHandler.loginUser(username, password);
        if (user != null)
        {
            getSessionBean1().setUser(user);

            if (BookingHandler.nextBookingIsGo(user.getMid()))
            {
                BookingHandler.makeComeBooking(user.getMid());
                return "loginSucessfull";
            }
            else
            {
                message = true;
            }

            return null;
        }
        else
        {
            status = "Benutzername/ Passwort ist falsch!";
        }

        return null;
    }

    /**
     * Prefaces the GO booking routine
     * @return "loginSucessfull"
     * @throws exceptions.DBException
     */
    public String goPushButton_action() throws DBException
    {
        UserBean user = UserHandler.loginUser(username, password);
        if (user != null)
        {
            getSessionBean1().setUser(user);

            if (BookingHandler.nextBookingIsGo(user.getMid()))
            {
                BookingHandler.makeGoBooking(user.getMid());
                return "loginSucessfull";
            }
            else
            {
                message = true;
            }

            return null;
        }
        else
        {
            status = "Benutzername/ Passwort ist falsch!";
        }

        return null;
    }

    /**
     * 
     * @return null
     */
    public String bookingTab_action()
    {
        return null;
    }

    /**
     * 
     * @return null
     */
    public String loginTab_action()
    {
        return null;
    }

    /**
     * Prefaces the offbeat booking routine
     * @return "loginSucessfull"
     * @throws exceptions.DBException
     */
    public String bookPushButton_action() throws DBException
    {
        UserBean user = getSessionBean1().getUser();
        if (user != null)
        {
            // außer Takt buchen, deshalb Prüfung umgedreht
            if (BookingHandler.nextBookingIsCome(user.getMid()))
            {
                BookingHandler.makeGoBooking(user.getMid());
            }
            else
            {
                BookingHandler.makeComeBooking(user.getMid());
            }

            message = false;
            return "loginSucessfull";
        }
        else
        {
            status = "Benutzername/ Passwort ist falsch!";
        }

        message = false;
        return null;
    }

    /**
     * 
     * @return null
     */
    public String dontBookPushButton_action()
    {
        message = false;
        return null;
    }

    /**
     * 
     * @return "forgotPassword"
     */
    public String newPasswordHyperlink_action()
    {
        return "forgotPassword";
    }

}

