package beans;

/**
 * Represents one row of the TimeAccount table
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

    /**
     * Creates a new instance of TimeAccountBean
     */
    public TimeAccountBean()
    {
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
     * @return minHaben
     */
    public int getMinHaben()
    {
        return minHaben;
    }

    /**
     * 
     * @param minHaben
     */
    public void setMinHaben(int minHaben)
    {
        this.minHaben = minHaben;
    }

    /**
     * 
     * @return minSaldo
     */
    public int getMinSaldo()
    {
        return minSaldo;
    }

    /**
     * 
     * @param minSaldo
     */
    public void setMinSaldo(int minSaldo)
    {
        this.minSaldo = minSaldo;
    }

    /**
     * 
     * @return minSoll
     */
    public int getMinSoll()
    {
        return minSoll;
    }

    /**
     * 
     * @param minSoll
     */
    public void setMinSoll(int minSoll)
    {
        this.minSoll = minSoll;
    }

    /**
     * 
     * @return period
     */
    public int getPeriod()
    {
        return period;
    }

    /**
     * 
     * @param period
     */
    public void setPeriod(int period)
    {
        this.period = period;
    }

    /**
     * 
     * @return year
     */
    public int getYear()
    {
        return year;
    }

    /**
     * 
     * @param year
     */
    public void setYear(int year)
    {
        this.year = year;
    }
    

}
