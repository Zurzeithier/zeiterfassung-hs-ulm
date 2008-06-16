package beans;

import java.util.Date;

/**
 *
 * @author manuel
 */
public class TimePostingBean
{

    private int bid;
    private int typId;
    private Date date;
    private int mid;
    private int kstId;
    private int koaId;

    /** Creates a new instance of TimePostingBean */
    public TimePostingBean()
    {
    }

    public int getBid()
    {
        return bid;
    }

    public void setBid(int bid)
    {
        this.bid = bid;
    }

    public Date getDate()
    {
        return date;
    }

    public void setDate(Date date)
    {
        this.date = date;
    }

    public int getKoaId()
    {
        return koaId;
    }

    public void setKoaId(int koaId)
    {
        this.koaId = koaId;
    }

    public int getKstId()
    {
        return kstId;
    }

    public void setKstId(int kstId)
    {
        this.kstId = kstId;
    }

    public int getMid()
    {
        return mid;
    }

    public void setMid(int mid)
    {
        this.mid = mid;
    }

    public int getTypId()
    {
        return typId;
    }

    public void setTypId(int typId)
    {
        this.typId = typId;
    }
    

}
