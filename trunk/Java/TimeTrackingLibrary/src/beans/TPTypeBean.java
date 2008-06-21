package beans;

/**
 * Represents one row of the TPType table
 * @author manuel
 */
public class TPTypeBean
{

    private int typId;
    private String name;
    private String symbol;

    /** Creates a new instance of TPTypeBean */
    public TPTypeBean()
    {
    }

    public String getName()
    {
        return name;
    }

    public void setName(String name)
    {
        this.name = name;
    }

    public String getSymbol()
    {
        return symbol;
    }

    public void setSymbol(String symbol)
    {
        this.symbol = symbol;
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
