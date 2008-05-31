/*
 * Page1.java
 *
 * Created on 31.05.2008, 00:19:45
 */
package timecapturing;

import beans.UserBean;
import business_logik.UserHandling;
import com.sun.rave.web.ui.appbase.AbstractPageBean;
import com.sun.webui.jsf.model.DefaultOptionsList;
import javax.faces.FacesException;

/**
 * <p>Page bean that corresponds to a similarly named JSP page.  This
 * class contains component definitions (and initialization code) for
 * all components that you have defined on this page, as well as
 * lifecycle methods and event handlers where you may add behavior
 * to respond to incoming events.</p>
 *
 * @author manuel
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
    }    // </editor-fold>

    private String newPassword;
    private UserBean userBean = new UserBean();
    private String statusLogin = "";
    private String statusChangePWD = "";

    public String getNewPassword()
    {
        return newPassword;
    }

    public void setNewPassword(String password)
    {
        this.newPassword = password;
    }

    public UserBean getUserBean()
    {
        return userBean;
    }

    public String getStatusChangePWD()
    {
        return statusChangePWD;
    }

    public void setStatusChangePWD(String statusChangePWD)
    {
        this.statusChangePWD = statusChangePWD;
    }

    public String getStatusLogin()
    {
        return statusLogin;
    }

    public void setStatusLogin(String statusLogin)
    {
        this.statusLogin = statusLogin;
    }

    private DefaultOptionsList menu1DefaultOptions = new DefaultOptionsList();

    public DefaultOptionsList getMenu1DefaultOptions()
    {
        return menu1DefaultOptions;
    }

    public void setMenu1DefaultOptions(DefaultOptionsList dol)
    {
        this.menu1DefaultOptions = dol;
    }

    private DefaultOptionsList menu2DefaultOptions = new DefaultOptionsList();

    public DefaultOptionsList getMenu2DefaultOptions()
    {
        return menu2DefaultOptions;
    }

    public void setMenu2DefaultOptions(DefaultOptionsList dol)
    {
        this.menu2DefaultOptions = dol;
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

    public String loginButton_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.

        if (UserHandling.getInstance().LoginUser(userBean))
        {
            return "loginSucessfull";
        }
        else
        {
            statusLogin = "Username and Password is wrong!";
        }

        return null;
    }

    public String changePwdButton_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.

        if (UserHandling.getInstance().ChangeUserPWD(userBean, newPassword))
        {
            statusChangePWD = "Change was successful!";
        }
        else
        {
            statusChangePWD = "Change failed!";
        }

        return null;
    }

    public String buchungKommenButton_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.

        if (UserHandling.getInstance().LoginUser(userBean))
        {
            return "loginSucessfull";
        }
        else
        {
            statusLogin = "Username and Password is wrong!";
        }

        return null;
    }

    public String buchungGehenButton_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.
        if (UserHandling.getInstance().LoginUser(userBean))
        {
            return "loginSucessfull";
        }
        else
        {
            statusLogin = "Username and Password is wrong!";
        }

        return null;
    }

    public String tab2_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.
        return null;
    }

    public String forgotPasswordHyperlink_action()
    {
        // TODO: Process the action. Return value is a navigation
        // case name where null will return to the same page.
                
        return "forgotPassword";
    }

}

