package beans;

import java.util.Date;

/**
 * Represents one row of the gui output table
 * @author manuel
 */
public class TimeBookingTableEntryBean 
{
    private String firstname = null;
    private String lastname = null;
    private Date comeBooking = null;
    private Date goBooking = null;

    /**
     * 
     * @return comeBooking
     */
    public Date getComeBooking()
    {
        return comeBooking;
    }

    /**
     * 
     * @param comeBooking
     */
    public void setComeBooking(Date comeBooking)
    {
        this.comeBooking = comeBooking;
    }

    /**
     * 
     * @return firstname
     */
    public String getFirstname()
    {
        return firstname;
    }

    /**
     * 
     * @param firstname
     */
    public void setFirstname(String firstname)
    {
        this.firstname = firstname;
    }

    /**
     * 
     * @return goBooking
     */
    public Date getGoBooking()
    {
        return goBooking;
    }

    /**
     * 
     * @param goBooking
     */
    public void setGoBooking(Date goBooking)
    {
        this.goBooking = goBooking;
    }

    /**
     * 
     * @return lastname
     */
    public String getLastname()
    {
        return lastname;
    }

    /**
     * 
     * @param lastname
     */
    public void setLastname(String lastname)
    {
        this.lastname = lastname;
    }
    
}
