package beans;

import java.util.Date;

/**
 * Represents one row of the TimeBooking table
 * @author manuel
 */
public class TimeBookingBean
{

    private int bid;
    private int typId;
    private Date date;
    private int mid;
    private int kstId;
    private int koaId;

    /**
     * Creates a new instance of TimeBookingBean
     */
    public TimeBookingBean()
    {
    }

    /**
     * 
     * @return bid
     */
    public int getBid()
    {
        return bid;
    }

    /**
     * 
     * @param bid
     */
    public void setBid(int bid)
    {
        this.bid = bid;
    }

    /**
     * 
     * @return date
     */
    public Date getDate()
    {
        return date;
    }

    /**
     * 
     * @param date
     */
    public void setDate(Date date)
    {
        this.date = date;
    }

    /**
     * 
     * @return koaId
     */
    public int getKoaId()
    {
        return koaId;
    }

    /**
     * 
     * @param koaId
     */
    public void setKoaId(int koaId)
    {
        this.koaId = koaId;
    }

    /**
     * 
     * @return kstId
     */
    public int getKstId()
    {
        return kstId;
    }

    /**
     * 
     * @param kstId
     */
    public void setKstId(int kstId)
    {
        this.kstId = kstId;
    }

    /**
     * 
     * @return mid
     */
    public int getMid()
    {
        return mid;
    }

    /**
     * 
     * @param mid
     */
    public void setMid(int mid)
    {
        this.mid = mid;
    }

    /**
     * 
     * @return typId
     */
    public int getTypId()
    {
        return typId;
    }

    /**
     * 
     * @param typId
     */
    public void setTypId(int typId)
    {
        this.typId = typId;
    }
    
    
}
