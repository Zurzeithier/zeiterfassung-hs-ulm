/*
 * TTSystem.java
 *
 * Created on 17.06.2008, 17:56:01
 */
package timetracking.secure;

import beans.TimeBookingTableEntryBean;
import beans.UserBean;
import com.sun.data.provider.impl.ObjectListDataProvider;
import com.sun.rave.web.ui.appbase.AbstractPageBean;
import com.sun.webui.jsf.component.TabSet;
import exceptions.DBException;
import handlers.BookingHandler;
import handlers.UserHandler;
import javax.faces.FacesException;
import javax.faces.convert.DateTimeConverter;
import timetracking.ApplicationBean1;
import timetracking.SessionBean1;

/**
 * <p>Page bean that corresponds to a similarly named JSP page.  This
 * class contains component definitions (and initialization code) for
 * all components that you have defined on this page, as well as
 * lifecycle methods and event handlers where you may add behavior
 * to respond to incoming events.</p>
 *
 * @author manuel
 */
public class TTSystem extends AbstractPageBean
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
    private String status = null;
    private boolean message = false;
    private ObjectListDataProvider bookings = new ObjectListDataProvider(TimeBookingTableEntryBean.class);

    public String getStatus()
    {
        return status;
    }

    public void setStatus(String status)
    {
        this.status = status;
    }

    public boolean isMessage()
    {
        return message;
    }

    public void setMessage(boolean message)
    {
        this.message = message;
    }

    public ObjectListDataProvider getBookings()
    {
        return bookings;
    }

    public void setBookings(ObjectListDataProvider bookings)
    {
        this.bookings = bookings;
    }

    private DateTimeConverter dateTimeConverter = new DateTimeConverter();

    public DateTimeConverter getDateTimeConverter()
    {
        return dateTimeConverter;
    }

    public void setDateTimeConverter(DateTimeConverter dtc)
    {
        this.dateTimeConverter = dtc;
    }

    private TabSet tabSet1 = new TabSet();

    public TabSet getTabSet1()
    {
        return tabSet1;
    }

    public void setTabSet1(TabSet ts)
    {
        this.tabSet1 = ts;
    }

    /**
     * <p>Construct a new Page bean instance.</p>
     */
    public TTSystem()
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
            log("TTSystem Initialization Failure", e);
            throw e instanceof FacesException ? (FacesException) e : new FacesException(e);
        }

        // </editor-fold>
        // Perform application initialization that must complete
        // *after* managed components are initialized
        // TODO - add your own initialization code here

        dateTimeConverter.setTimeZone(null);
        dateTimeConverter.setPattern("dd.MM.yyyy 'um' HH:mm 'Uhr'");
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
        try
        {
            bookings.setList(BookingHandler.getBookings(getSessionBean1().getUser().getMid(), 10));
        }
        catch (DBException ex)
        {
            log(ex.getMessage());
        }
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
    protected ApplicationBean1 getApplicationBean1()
    {
        return (ApplicationBean1) getBean("ApplicationBean1");
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

    public String logoutButton_action()
    {
        getSessionBean1().setUser(null);

        return "logout";
    }

    public String tab1_action()
    {
        return null;
    }

    public String tab2_action()
    {
        return null;
    }

    public String changePasswordButton_action() throws DBException
    {
        if (UserHandler.changeUser(getSessionBean1().getUser().getMid(), getSessionBean1().getUser()))
        {
            status = "Änderungen erfolgreich übernommen!";
        }
        else
        {
            status = "Änderung fehlgeschlagen!";
        }

        return null;
    }

    public String comePushButton_action() throws DBException
    {
        UserBean user = getSessionBean1().getUser();

        if (BookingHandler.nextBookingIsCome(user.getMid()))
        {
            BookingHandler.makeComeBooking(user.getMid());
        }
        else
        {
            message = true;
        }

        return null;
    }

    public String goPushButton_action() throws DBException
    {
        UserBean user = getSessionBean1().getUser();

        if (BookingHandler.nextBookingIsGo(user.getMid()))
        {
            BookingHandler.makeGoBooking(user.getMid());
        }
        else
        {
            message = true;
        }

        return null;
    }

    public String bookPushButton_action() throws DBException
    {
        UserBean user = getSessionBean1().getUser();

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
        return null;
    }

    public String dontBookPushButton_action()
    {
        message = false;
        return null;
    }
}