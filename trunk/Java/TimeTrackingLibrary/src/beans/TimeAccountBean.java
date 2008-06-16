package beans;

/**
 *
 * @author manuel
 */
public class TimeAccountBean
{
    private int mid;
    private int period;
    private int year;
    private int minSoll;
    private int minHaben;
    private int minSaldo;

    /** Creates a new instance of TimeAccountBean */
    public TimeAccountBean()
    {
    }

    public int getMid()
    {
        return mid;
    }

    public void setMid(int mid)
    {
        this.mid = mid;
    }

    public int getMinHaben()
    {
        return minHaben;
    }

    public void setMinHaben(int minHaben)
    {
        this.minHaben = minHaben;
    }

    public int getMinSaldo()
    {
        return minSaldo;
    }

    public void setMinSaldo(int minSaldo)
    {
        this.minSaldo = minSaldo;
    }

    public int getMinSoll()
    {
        return minSoll;
    }

    public void setMinSoll(int minSoll)
    {
        this.minSoll = minSoll;
    }

    public int getPeriod()
    {
        return period;
    }

    public void setPeriod(int period)
    {
        this.period = period;
    }

    public int getYear()
    {
        return year;
    }

    public void setYear(int year)
    {
        this.year = year;
    }
    

}
