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

    public Date getComeBooking()
    {
        return comeBooking;
    }

    public void setComeBooking(Date comeBooking)
    {
        this.comeBooking = comeBooking;
    }

    public String getFirstname()
    {
        return firstname;
    }

    public void setFirstname(String firstname)
    {
        this.firstname = firstname;
    }

    public Date getGoBooking()
    {
        return goBooking;
    }

    public void setGoBooking(Date goBooking)
    {
        this.goBooking = goBooking;
    }

    public String getLastname()
    {
        return lastname;
    }

    public void setLastname(String lastname)
    {
        this.lastname = lastname;
    }
    
}
